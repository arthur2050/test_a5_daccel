<?php

namespace App\Message;

class CheckTaskDeadlineMessage
{
	/**
	 * @param int $taskId task id
	 */
	public function __construct(private int $taskId)
	{
	}

	/**
	 * @return int
	 */
	public function getTaskId(): int
	{
		return $this->taskId;
	}
}