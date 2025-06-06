<?php

namespace App\Services\Api;

use App\DTO\Api\CreateTaskDto;
use App\DTO\Api\UpdateTaskDto;
use App\Entity\Task;
use App\Entity\User;
use App\Enum\Api\TaskStatus;
use App\Exceptions\Api\TaskNotFoundException;
use App\Factory\TaskFactory;
use App\Repository\TaskRepository;
use Doctrine\ORM\EntityManagerInterface;

class TaskCrudHelper implements TaskCrudHelperInterface
{
	/**
	 * @param TaskRepository $repository repository
	 * @param EntityManagerInterface $em em
	 * @param TaskFactory $taskFactory taskFactory
	 */
	public function __construct(
		private TaskRepository $repository,
		private EntityManagerInterface $em,
		private TaskFactory $taskFactory,
	) {}

	/**
	 * @param CreateTaskDto $dto dto
	 * @param User $user user
	 *
	 * @return Task
	 */
	public function create(CreateTaskDto $dto, User $user): Task
	{
		$task = $this->taskFactory->create($dto, $user);
		$this->em->persist($task);
		$this->em->flush();

		return $task;
	}

	/**
	 * @param UpdateTaskDto  dto
	 * @param User $user user
	 * @return Task
	 *
	 * @throws \DateMalformedStringException
	 */
	public function update(UpdateTaskDto $dto, User $user): Task
	{
		$task = $this->findByIdAndUser($dto->id, $user);

		if (isset($dto->title)) $task->setTitle($dto->title);
		if (isset($dto->description)) $task->setDescription($dto->description);
		if (isset($dto->status) && TaskStatus::tryFrom($dto->status)) {
			$task->setStatus(TaskStatus::from($dto->status));
		}
		if (isset($dto->deadline)) {
			$task->setDeadline(new \DateTimeImmutable($dto->deadline));
		}

		$this->em->persist($task);
		$this->em->flush();

		return $task;
	}

	/**
	 * @param int $id id
	 * @param User $user user
	 *
	 * @return void
	 */
	public function delete(int $id, User $user)
	{
		$task = $this->findByIdAndUser($id, $user);
		$this->em->remove($task);
		$this->em->flush();
	}

	/***
	 * @param int $id id
	 * @param User $user user
	 *
	 * @return Task
	 */
	public function show(int $id, User $user): Task
	{
		$task = $this->findByIdAndUser($id, $user);

		return $task;
	}

	/**
	 * @param int $id id
	 * @param User $user user
	 *
	 * @return Task
	 */
	private function findByIdAndUser(int $id, User $user): Task
	{
		$task = $this->repository->findByIdAndUser($id, $user);

		if (!$task) {
			throw new TaskNotFoundException("Задача с id: $id не найдена или вы не имеете к ней доступа.");
		}

		return $task;
	}
}