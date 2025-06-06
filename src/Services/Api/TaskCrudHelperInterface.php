<?php

namespace App\Services\Api;

use App\DTO\Api\CreateTaskDto;
use App\DTO\Api\UpdateTaskDto;
use App\Entity\Task;
use App\Entity\User;

interface TaskCrudHelperInterface
{
	/**
	 * @param CreateTaskDto $dto dto
	 * @param User $user user
	 *
	 * @return Task
	 */
	public function create(CreateTaskDto $dto, User $user): Task;

	/**
	 * @param UpdateTaskDto  dto
	 * @param User $user user
	 * @return Task
	 *
	 * @throws \DateMalformedStringException
	 */
	public function update(UpdateTaskDto $dto, User $user): Task;

	/**
	 * @param int $id id
	 * @param User $user user
	 *
	 * @return void
	 */
	public function delete(int $id, User $user);

	/***
	 * @param int $id id
	 * @param User $user user
	 *
	 * @return Task
	 */
	public function show(int $id, User $user);
}