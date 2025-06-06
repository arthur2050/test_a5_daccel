<?php

// src/Controller/Admin/DashboardController.php

namespace App\Controller\Crud;

use EasyCorp\Bundle\EasyAdminBundle\Attribute\AdminDashboard;
use EasyCorp\Bundle\EasyAdminBundle\Config\MenuItem;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractDashboardController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use EasyCorp\Bundle\EasyAdminBundle\Router\AdminUrlGenerator;
use App\Entity\Task;

#[AdminDashboard]
class DashboardController extends AbstractDashboardController
{
	#[Route('/admin', name: 'admin')]
	public function index(): Response
	{
		// Автоматически редиректим на список задач
		$adminUrlGenerator = $this->container->get(AdminUrlGenerator::class);

		return $this->redirect(
			$adminUrlGenerator->setController(TaskCrudController::class)->generateUrl()
		);
	}

	/**
	 * @return iterable
	 */
	public function configureMenuItems(): iterable
	{
		yield MenuItem::linktoDashboard('Dashboard', 'fa fa-home');
		yield MenuItem::linkToCrud('Tasks', 'fas fa-tasks', Task::class);
	}
}
