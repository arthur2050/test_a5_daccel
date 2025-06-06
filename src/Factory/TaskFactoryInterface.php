<?php

namespace App\Factory;

use App\DTO\Api\CreateTaskDto;
use App\Entity\Task;
use App\Entity\User;

interface TaskFactoryInterface
{
	/**
	 * @param CreateTaskDto $dto dto
	 * @param User $user user
	 * @return Task
	 *
	 * @throws \DateMalformedStringException
	 */
	public function create(CreateTaskDto $dto, User $user): Task;
}