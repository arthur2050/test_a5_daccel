<?php

namespace App\Controller\Api;

use App\DTO\Api\UserRegistrationDto;
use App\Factory\UserRegistrationFactoryInterface;
use Doctrine\DBAL\Exception\UniqueConstraintViolationException;
use Lexik\Bundle\JWTAuthenticationBundle\Services\JWTTokenManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpKernel\Attribute\MapRequestPayload;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Validator\Validator\ValidatorInterface;
use Nelmio\ApiDocBundle\Attribute\Model;
use OpenApi\Attributes as OA;
#[Route('/api/v1')]
class UserController extends AbstractController
{
	/**
	 * @param UserRegistrationFactoryInterface $userRegistrationFactory
	 * @param EntityManagerInterface $em
	 * @param ValidatorInterface $validator
	 *
	 * @param JWTTokenManagerInterface $jwtManager
	 */
	public function __construct(private UserRegistrationFactoryInterface $userRegistrationFactory,
								private EntityManagerInterface $em,
								private ValidatorInterface $validator,
								private JWTTokenManagerInterface $jwtManager
	)
	{

	}

	/**
	 * @param UserRegistrationDto $userRegistrationDto dto
	 *
	 * @return JsonResponse
	 */
	#[OA\Post(
		path: '/api/v1/jwt/register',
		summary: 'Регистрация пользователя и получение JWT токена',
		requestBody: new OA\RequestBody(
			required: true,
			content: new OA\JsonContent(ref: new Model(type: UserRegistrationDto::class))
		),
		tags: ['User'],
		responses: [
			new OA\Response(
				response: 201,
				description: 'Пользователь успешно зарегистрирован, возвращается JWT',
				content: new OA\JsonContent(
					properties: [
						new OA\Property(property: 'token', type: 'string', example: 'eyJ0eXAiOiJKV1QiLCJhb...')
					],
					type: 'object'
				)
			),
			new OA\Response(response: 400, description: 'Ошибки валидации'),
			new OA\Response(response: 409, description: 'Пользователь с таким email уже существует'),
			new OA\Response(response: 500, description: 'Ошибка сервера'),
		]
	)]
	#[Route('/jwt/register', name: 'api_user_jwt_register', methods: ['POST'])]
	public function register(
		#[MapRequestPayload] UserRegistrationDto $userRegistrationDto,
	): JsonResponse {
		try {
			$errors = $this->validator->validate($userRegistrationDto);
			if (count($errors) > 0) {
				$messages = [];
				foreach ($errors as $error) {
					$messages[$error->getPropertyPath()] = $error->getMessage();
				}
				return $this->json(['errors' => $messages], 400);
			}

			$user = $this->userRegistrationFactory->create($userRegistrationDto);
			$this->em->persist($user);
			$this->em->flush();

			$token = $this->jwtManager->create($user);

			return $this->json(['token' => $token], 201);
		} catch (UniqueConstraintViolationException $e) {
			return $this->json([
				'errors' => [
					'email' => 'Пользователь с таким email уже существует.'
				]
			], 409); // 409 Conflict
		} catch (\Throwable $e) {
			return $this->json([
				'errors' => ['server' => 'Что-то пошло не так.']
			], 500);
		}
	}

	/**
	 * @return JsonResponse
	 */
	#[OA\Post(
		path: '/api/v1/jwt/logout',
		summary: 'Выход пользователя (удаление токена на клиенте)',
		tags: ['User'],
		responses: [
			new OA\Response(
				response: 200,
				description: 'Выход успешен',
				content: new OA\JsonContent(
					properties: [
						new OA\Property(property: 'message', type: 'string', example: 'Клиент должен удалить токен JWT вручную.')
					],
					type: 'object'
				)
			)
		]
	)]
	#[Route('/jwt/logout', name: 'api_user_jwt_logout', methods: ['POST'])]
	public function logout(): JsonResponse
	{
		return new JsonResponse(['message' => 'Клиент должен удалить токен JWT вручную.']);
	}
}