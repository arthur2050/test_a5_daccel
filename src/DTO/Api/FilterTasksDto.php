<?php

namespace App\DTO\Api;
use App\Enum\Api\TaskStatus;
use Symfony\Component\Validator\Constraints as Assert;

class FilterTasksDto
{
	#[Assert\Choice(callback: [TaskStatus::class, 'values'], message: 'Недопустимый статус задачи.')]
	public ?string $status = null;

	#[Assert\Date(message: 'Неверный формат даты. Ожидается формат Y-m-d.')]
	public ?string $deadline = null;
}