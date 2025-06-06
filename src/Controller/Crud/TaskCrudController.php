<?php

namespace App\Controller\Crud;

use App\Entity\Task;
use App\Enum\Api\TaskStatus;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\AssociationField;
use EasyCorp\Bundle\EasyAdminBundle\Field\ChoiceField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextareaField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;
use EasyCorp\Bundle\EasyAdminBundle\Field\DateTimeField;
class TaskCrudController extends AbstractCrudController
{
	/**
	 * @return string
	 */
	public static function getEntityFqcn(): string
	{
		return Task::class;
	}

	/**
	 * @param string $pageName page name
	 *
	 * @return iterable
	 */
	public function configureFields(string $pageName): iterable
	{
		yield TextField::new('title', 'Title');

		yield TextareaField::new('description', 'Description')
			->hideOnIndex(); // описание не обязательно показывать в списке

		yield ChoiceField::new('status', 'Status')
			->setChoices([
				'New' => TaskStatus::NEW,
				'In Progress' => TaskStatus::IN_PROGRESS,
				'Done' => TaskStatus::DONE,
				// добавь остальные статусы, если есть
			]);

		yield DateTimeField::new('deadline', 'Deadline');

		yield AssociationField::new('owner', 'Owner')
			->formatValue(fn ($value, $entity) => $entity->getOwner()?->getId())
			->setRequired(true);

		// если хочешь, можно добавить createdAt (из трейта), но обычно его показывают только для чтения
		yield DateTimeField::new('createdAt', 'Created At')
			->onlyOnDetail()
			->setFormTypeOptions(['disabled' => true]);
	}

	public function configureCrud(Crud $crud): Crud
	{
		return $crud
			->setEntityLabelInSingular('Task')
			->setEntityLabelInPlural('Tasks')
			->setPageTitle(Crud::PAGE_INDEX, 'Tasks List')
			->setDefaultSort(['deadline' => 'ASC']);
	}
}