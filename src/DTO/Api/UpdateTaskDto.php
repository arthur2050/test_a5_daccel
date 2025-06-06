<?php

namespace App\DTO\Api;

use App\Enum\Api\TaskStatus;
use Symfony\Component\Validator\Constraints as Assert;

class UpdateTaskDto
{
	#[Assert\NotBlank]
	#[Assert\Type('integer')]
	public int $id;

	#[Assert\Length(max: 255)]
	public ?string $title = null;

	#[Assert\Length(max: 1000)]
	public ?string $description = null;

	#[Assert\DateTime]
	public ?string $deadline = null;

	#[Assert\Choice(callback: [TaskStatus::class, 'values'], message: 'Недопустимый статус задачи.')]
	public ?string $status = null;
}