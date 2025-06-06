<?php

namespace App\EventSubscriber;

use App\Entity\User;
use App\Security\RequireAuthUserInterface;
use Symfony\Bundle\SecurityBundle\Security;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpKernel\Event\ControllerEvent;
use Symfony\Component\HttpKernel\KernelEvents;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;

class EnsureUserIsAuthenticatedListener implements EventSubscriberInterface
{
	/**
	 * @param Security $security security
	 */
	public function __construct(private Security $security) {}

	public function onKernelController(ControllerEvent $event)
	{
		$controller = $event->getController();

		if (!is_array($controller)) {
			return;
		}

		[$controllerObject, $method] = $controller;

		if (!$controllerObject instanceof RequireAuthUserInterface) {
			return; // Не нужно проверять этого контроллера
		}

		$user = $this->security->getUser();
		if (!$user instanceof User) {
			throw new AccessDeniedException('Вы не авторизованы');
		}
	}

	public static function getSubscribedEvents(): array
	{
		return [
			KernelEvents::CONTROLLER => 'onKernelController',
		];
	}
}