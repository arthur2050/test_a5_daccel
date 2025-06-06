<?php

namespace App\Services\Api;

use App\DTO\Api\FilterTasksDto;
use App\Entity\User;
use App\Enum\Api\TaskStatus;

interface TaskFetcherInterface
{
	/**
	 * @param User $user user
	 * @param FilterTasksDto $dto dto
	 *
	 * @return array
	 */
	public function filter(User $user, FilterTasksDto $dto): array;
}