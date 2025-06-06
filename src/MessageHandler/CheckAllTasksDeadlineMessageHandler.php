<?php

namespace App\MessageHandler;

use App\Message\CheckAllTasksDeadlineMessage;
use App\Message\CheckTaskDeadlineMessage;
use App\Repository\TaskRepository;
use Psr\Log\LoggerInterface;
use Symfony\Component\Messenger\Attribute\AsMessageHandler;
use Symfony\Component\Messenger\MessageBusInterface;

#[AsMessageHandler]
class CheckAllTasksDeadlineMessageHandler
{
	/**
	 * @var int|string
	 */
	private readonly int $taskDeadlineTimeToNotifyBefore;

	/**
	 * @param TaskRepository $taskRepository task repository
	 * @param MessageBusInterface $bus bus of events
	 * @param LoggerInterface $logger logger
	 * @param string $taskDeadlineTimeToNotifyBefore time to notify before
	 */
	public function __construct(
		private TaskRepository $taskRepository,
		private MessageBusInterface $bus,
		private LoggerInterface $logger,
		string $taskDeadlineTimeToNotifyBefore
	) {
		$this->taskDeadlineTimeToNotifyBefore = $taskDeadlineTimeToNotifyBefore;
	}

	/**
	 * @param CheckAllTasksDeadlineMessage $message message
	 * @return void
	 * @throws \Symfony\Component\Messenger\Exception\ExceptionInterface
	 */
	public function __invoke(CheckAllTasksDeadlineMessage $message): void
	{
		$this->logger->info("Running CheckAllTasksDeadlineMessageHandler");
		$now = new \DateTimeImmutable();
		$upcoming = $now->modify('+'.$this->taskDeadlineTimeToNotifyBefore.' seconds');
		$this->logger->info("Window: From {$now->format('Y-m-d H:i:s')} to {$upcoming->format('Y-m-d H:i:s')}");

		$tasks = $this->taskRepository->createQueryBuilder('t')
			->where('t.deadline BETWEEN :now AND :upcoming')
			->setParameter('now', $now)
			->setParameter('upcoming', $upcoming)
			->getQuery()
			->getResult();


		foreach ($tasks as $task) {
			$this->logger->info("Dispatching CheckTaskDeadlineMessage for task id: " . $task->getId());
			$this->bus->dispatch(new CheckTaskDeadlineMessage($task->getId()));
		}
	}
}