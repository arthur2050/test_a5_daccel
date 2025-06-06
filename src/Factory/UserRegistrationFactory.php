<?php

namespace App\Factory;

use App\DTO\Api\UserRegistrationDto;
use App\Entity\User;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class UserRegistrationFactory implements UserRegistrationFactoryInterface
{

	/**
	 * @param UserPasswordHasherInterface $passwordHasher password hasher
	 */
	public function __construct(private UserPasswordHasherInterface $passwordHasher)
	{

	}

	/**
	 * @param UserRegistrationDto $userRegistrationDto
	 *
	 * @return User
	 */
	public function create(UserRegistrationDto $userRegistrationDto): User
	{
		$user = new User();
		$user->setEmail($userRegistrationDto->email);
		$user->setName($userRegistrationDto->name);
		$user->setPassword($this->passwordHasher->hashPassword($user, $userRegistrationDto->password));

		return $user;
	}
}