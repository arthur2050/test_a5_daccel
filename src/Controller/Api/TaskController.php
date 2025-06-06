<?php

namespace App\Controller\Api;

use App\DTO\Api\CreateTaskDto;
use App\DTO\Api\FilterTasksDto;
use App\DTO\Api\UpdateTaskDto;
use App\Entity\Task;
use App\Handlers\ValidationErrorsHandler;
use App\Message\CheckTaskDeadlineMessage;
use App\Security\RequireAuthUserInterface;
use App\Services\Api\TaskCrudHelperInterface;
use App\Services\Api\TaskFetcherInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Messenger\MessageBusInterface;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Component\Validator\Validator\ValidatorInterface;
use Nelmio\ApiDocBundle\Attribute\Model;
use Nelmio\ApiDocBundle\Attribute\Security;
use OpenApi\Attributes as OA;
#[Security(name: 'Bearer')]
#[IsGranted('ROLE_USER')]
#[Route('/api/v1')]
class TaskController extends AbstractController implements RequireAuthUserInterface
{
	public function __construct(
		private SerializerInterface     $serializer,
		private ValidatorInterface      $validator,
		private TaskCrudHelperInterface $crudHelper,
		private ValidationErrorsHandler $validationErrorsHandler,
		private MessageBusInterface     $bus,
		private TaskFetcherInterface    $taskFetcher,
	)
	{

	}

	/**
	 * @param Request $request request
	 *
	 * @return JsonResponse
	 */
	#[OA\Get(
		path: '/api/v1/tasks',
		summary: 'Получить список задач',
		security: [['bearerAuth' => []]],
		tags: ['Tasks'],
		responses: [
			new OA\Response(
				response: 200,
				description: 'Успешный ответ, список задач',
				content: new OA\JsonContent(
					type: 'array',
					items: new OA\Items(ref: new Model(type: Task::class))
				)
			),
			new OA\Response(
				response: 422,
				description: 'Ошибки валидации',
				content: new OA\JsonContent(
					properties: [
						new OA\Property(property: 'errors', type: 'array', items: new OA\Items(type: 'string'))
					],
					type: 'object'
				)
			)
		]
	)]
	#[Route('/tasks', name: 'tasks', methods: ['GET'])]
	public function index(Request $request)
	{
		$user = $this->getUser();
		$queryParams = array_intersect_key(
			$request->query->all(),
			array_flip(['status', 'deadline'])
		);
		/** @var FilterTasksDto $filterDto */
		$filterDto = $this->serializer->denormalize($queryParams, FilterTasksDto::class);

		$errors = $this->validator->validate($filterDto);
		if (count($errors) > 0) {
			$messages = $this->validationErrorsHandler->format($errors);
			return $this->json(['errors' => $messages], 422);
		}
		$tasks = $this->taskFetcher->filter($user, $filterDto);
		return $this->json($tasks);
	}

	/**
	 * @param Request $request request
	 * @return JsonResponse
	 * @throws \Symfony\Component\Messenger\Exception\ExceptionInterface
	 */
	#[OA\Post(
		path: '/api/v1/task',
		summary: 'Создать новую задачу',
		security: [['bearerAuth' => []]],
		requestBody: new OA\RequestBody(
			required: true,
			content: new OA\JsonContent(ref: new Model(type: CreateTaskDto::class))
		),
		tags: ['Tasks'],
		responses: [
			new OA\Response(
				response: 201,
				description: 'Задача успешно создана',
				content: new OA\JsonContent(
					properties: [
						new OA\Property(property: 'task', type: 'string', example: 'Задача с id:1 успешно создана.')
					],
					type: 'object'
				)
			),
			new OA\Response(response: 422, description: 'Ошибки валидации'),
		]
	)]
	#[Route('/task', name: 'api_task_create', methods: ['POST'])]
	public function create(Request $request)
	{
		$user = $this->getUser();
		$createTaskDto = $this->serializer->deserialize($request->getContent(), CreateTaskDto::class, 'json');

		$errors = $this->validator->validate($createTaskDto);
		if (count($errors) > 0) {
			$messages = $this->validationErrorsHandler->format($errors);
			return $this->json(['errors' => $messages], 422);
		}
		$task = $this->crudHelper->create($createTaskDto, $user);
		$this->bus->dispatch(new CheckTaskDeadlineMessage($task->getId()));

		return $this->json(['task' => "Задача с id:{$task->getId()} успешно создана."], 201);
	}

	/**
	 * @param int $id id
	 *
	 * @return JsonResponse
	 */
	#[OA\Get(
		path: '/api/v1/task/{id}',
		summary: 'Получить задачу по ID',
		tags: ['Tasks'],
		parameters: [
			new OA\Parameter(
				name: 'id',
				description: 'ID задачи',
				in: 'path',
				required: true,
				schema: new OA\Schema(type: 'integer')
			)
		],
		responses: [
			new OA\Response(
				response: 200,
				description: 'Задача успешно получена',
				content: new OA\JsonContent(ref: new Model(type: Task::class))
			),
			new OA\Response(response: 404, description: 'Задача не найдена'),
		]
	)]
	#[Route('/task/{id}', name: 'api_task_show', methods: ['GET'])]
	public function show(int $id): JsonResponse
	{
		$user = $this->getUser();
		$task = $this->crudHelper->show($id, $user);

		return $this->json($task);
	}

	/**
	 * @param Request $request request
	 *
	 * @return JsonResponse
	 */
	#[OA\Put(
		path: '/api/v1/task',
		summary: 'Обновить задачу',
		requestBody: new OA\RequestBody(
			required: true,
			content: new OA\JsonContent(ref: new Model(type: UpdateTaskDto::class))
		),
		tags: ['Tasks'],
		responses: [
			new OA\Response(response: 200, description: 'Задача успешно обновлена'),
			new OA\Response(response: 404, description: 'Задача не найдена'),
			new OA\Response(response: 422, description: 'Ошибки валидации'),
		]
	)]
	#[Route('/task', name: 'api_task_update', methods: ['PUT'])]
	public function update(Request $request)
	{
		$user = $this->getUser();
		$updateTaskDto = $this->serializer->deserialize($request->getContent(), UpdateTaskDto::class, 'json');

		$errors = $this->validator->validate($updateTaskDto);
		if (count($errors) > 0) {
			$messages = $this->validationErrorsHandler->format($errors);
			return $this->json(['errors' => $messages], 422);
		}

		$task = $this->crudHelper->update($updateTaskDto, $user);

		return $this->json(['task' => "Задача с id:{$task->getId()} успешно изменена."], 200);
	}

	/**
	 * @param int $id id
	 *
	 * @return JsonResponse
	 */
	#[OA\Delete(
		path: '/api/v1/task/{id}',
		summary: 'Удалить задачу по ID',
		tags: ['Tasks'],
		parameters: [
			new OA\Parameter(
				name: 'id',
				description: 'ID задачи',
				in: 'path',
				required: true,
				schema: new OA\Schema(type: 'integer')
			)
		],
		responses: [
			new OA\Response(response: 204, description: 'Задача успешно удалена'),
			new OA\Response(response: 404, description: 'Задача не найдена'),
		]
	)]
	#[Route('/task/{id}', name: 'api_task_delete', methods: ['DELETE'])]
	public function delete(int $id): JsonResponse
	{
		$user = $this->getUser();
		$task = $this->crudHelper->delete($id, $user);

		return $this->json($task, 204);
	}
}