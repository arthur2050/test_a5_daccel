<?php

namespace App\MessageHandler;

use App\Entity\Task;
use App\Message\CheckTaskDeadlineMessage;
use Doctrine\ORM\EntityManagerInterface;
use Psr\Log\LoggerInterface;
use Symfony\Component\Messenger\Attribute\AsMessageHandler;

#[AsMessageHandler]
class CheckTaskDeadlineMessageHandler
{
	/**
	 * @var int
	 */
	private readonly int $taskDeadlineTimeToNotifyWindow;

	/**
	 * @var int
	 */
	private readonly int $taskDeadlineTimeToNotifyBefore;

	/**
	 * @param EntityManagerInterface $em em
	 * @param LoggerInterface $logger logger
	 * @param string $taskDeadlineTimeToNotifyWindow a window time to catch a notification
	 * @param string $taskDeadlineTimeToNotifyBefore time to notify before
	 */
	public function __construct(private EntityManagerInterface $em,
								private LoggerInterface $logger,
								string $taskDeadlineTimeToNotifyWindow,
								string $taskDeadlineTimeToNotifyBefore)
	{
		$this->taskDeadlineTimeToNotifyWindow = (int) $taskDeadlineTimeToNotifyWindow;
		$this->taskDeadlineTimeToNotifyBefore = (int) $taskDeadlineTimeToNotifyBefore;
	}

	/**
	 * @param CheckTaskDeadlineMessage $message message
	 *
	 * @return void
	 */
	public function __invoke(CheckTaskDeadlineMessage $message)
	{
		$task = $this->em->getRepository(Task::class)->find($message->getTaskId());

		if (!$task) {
			return;
		}

		$deadline = $task->getDeadline();
		$now = new \DateTimeImmutable();
		$diff = $deadline->getTimestamp() - $now->getTimestamp();

		$this->logger->info(get_class($this). " deadline: {$diff} seconds");
		if ($diff <= $this->taskDeadlineTimeToNotifyBefore
			&& $diff > $this->taskDeadlineTimeToNotifyBefore - $this->taskDeadlineTimeToNotifyWindow) { //напоминаем за два часа 60*60*2
			// Тут можно отправить уведомление: email, push, лог, телега и т.п.
			// Пример: лог
			file_put_contents(__DIR__ . '/../../var/log/deadline_debug.log', sprintf(
				"Task #%d:\nDeadline: %s\nNow: %s\nDiff: %d sec\n\n",
				$task->getId(),
				$deadline->format('Y-m-d H:i:s P'),
				$now->format('Y-m-d H:i:s P'),
				$diff
			), FILE_APPEND);
		}
	}
}