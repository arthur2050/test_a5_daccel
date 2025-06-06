<?php

namespace App\EventSubscriber;

use App\Exceptions\Api\TaskNotFoundException;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpKernel\Event\ExceptionEvent;
use Symfony\Component\HttpKernel\KernelEvents;
use Symfony\Component\Serializer\Exception\NotEncodableValueException;

class ApiExceptionSubscriber implements EventSubscriberInterface
{
	/**
	 * @return string[]
	 */
	public static function getSubscribedEvents(): array
	{
		return [
			KernelEvents::EXCEPTION => 'onKernelException',
		];
	}


	/**
	 * @param ExceptionEvent $event event
	 *
	 * @return void
	 */
	public function onKernelException(ExceptionEvent $event): void
	{
		$exception = $event->getThrowable();

		// Обработка отсутствующей по id таски
		if ($exception instanceof TaskNotFoundException) {
			$response = new JsonResponse([
				'error' => 'Not found',
				'message' => $exception->getMessage(),
			], JsonResponse::HTTP_NOT_FOUND);

			$event->setResponse($response);
		}

		if ($exception instanceof \InvalidArgumentException) {
			$response = new JsonResponse([
				'error' => 'Bad request',
				'message' => $exception->getMessage(),
			], JsonResponse::HTTP_BAD_REQUEST);

			$event->setResponse($response);
			return;
		}

		if ($exception instanceof NotEncodableValueException) {
			$response = new JsonResponse([
				'error' => 'Invalid JSON',
				'message' => $exception->getMessage(),
			], JsonResponse::HTTP_BAD_REQUEST);

			$event->setResponse($response);
			return;
		}
	}
}