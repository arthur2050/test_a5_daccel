<?php

namespace App\Services\Api;

use App\DTO\Api\FilterTasksDto;
use App\Entity\User;
use App\Repository\TaskRepository;

class TaskFetcher implements TaskFetcherInterface
{
	/**
	 * @param TaskRepository $taskRepository task repository
	 */
	public function __construct(private readonly TaskRepository $taskRepository)
	{

	}

	/**
	 * @param User $user user
	 * @param FilterTasksDto $dto dto
	 *
	 * @return array
	 */
	public function filter(User $user, FilterTasksDto $dto): array
	{
		return $this->taskRepository->filterByStatusAndDeadline($user, $dto->status, $dto->deadline);
	}
}