<?php

namespace App\Scheduler;

use App\Message\CheckAllTasksDeadlineMessage;
use Symfony\Component\Scheduler\Attribute\AsSchedule;
use Symfony\Component\Scheduler\RecurringMessage;
use Symfony\Component\Scheduler\Schedule;
use Symfony\Component\Scheduler\ScheduleProviderInterface;

#[AsSchedule('check_deadlines_tasks')]
class CheckDeadlinesTask  implements ScheduleProviderInterface
{
	/**
	 * @var string|int
	 */
	private readonly string $taskDeadlineSchedulerInterval;

	/**
	 * @param int $taskDeadlineSchedulerInterval interval for scheduling
	 */
	public function __construct(
		int $taskDeadlineSchedulerInterval,
	) {
		$this->taskDeadlineSchedulerInterval = $taskDeadlineSchedulerInterval;
	}
	public function getSchedule(): Schedule
	{
		$frequency = $this->taskDeadlineSchedulerInterval." seconds";
		return (new Schedule())->with(
			RecurringMessage::every($frequency, new CheckAllTasksDeadlineMessage())
		);

	}
}