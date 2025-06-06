<?php

namespace App\DTO\Api;

use Symfony\Component\Validator\Constraints as Assert;
use OpenApi\Attributes as OA;

class UserRegistrationDto
{
	#[OA\Property(type: 'string', description: 'Имя пользователя')]
	#[Assert\NotBlank]
	public string $name;

	#[OA\Property(type: 'string', description: 'Email пользователя', example: 'user@example.com')]
	#[Assert\NotBlank]
	#[Assert\Email]
	public string $email;

	#[OA\Property(type: 'string', description: 'Пароль')]
	#[Assert\NotBlank]
	public string $password;

	/**
	 * @param string $name name
	 * @param string $email email
	 * @param string $password password
	 */
	public function __construct(
		string $name,
		string $email,
		string $password,
	){
		$this->name = $name;
		$this->email = $email;
		$this->password = $password;
	}
}