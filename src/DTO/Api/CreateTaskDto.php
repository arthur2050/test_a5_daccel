<?php

namespace App\DTO\Api;

use Symfony\Component\Validator\Constraints as Assert;

class CreateTaskDto
{
    #[Assert\NotBlank]
	#[Assert\Length(max: 255)]
	public string $title;

    #[Assert\Length(max: 1000)]
	public ?string $description = null;

    #[Assert\NotBlank]
	#[Assert\DateTime]
	public string $deadline;
}