<?php

namespace App\Factory;

use App\DTO\Api\CreateTaskDto;
use App\Entity\Task;
use App\Entity\User;
use App\Enum\Api\TaskStatus;

class TaskFactory implements TaskFactoryInterface
{

	/**
	 * @param CreateTaskDto $dto dto
	 * @param User $user user
	 * @return Task
	 *
	 * @throws \DateMalformedStringException
	 */
	public function create(CreateTaskDto $dto, User $user): Task
	{
		$task = new Task();
		$task->setTitle($dto->title);
		$task->setDescription($dto->description);
		$task->setDeadline(new \DateTimeImmutable($dto->deadline));
		$task->setStatus(TaskStatus::NEW);
		$task->setOwner($user);
		$task->setCreatedAt(new \DateTimeImmutable());

		return $task;
	}
}