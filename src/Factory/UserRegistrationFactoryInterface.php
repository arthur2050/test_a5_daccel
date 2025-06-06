<?php

namespace App\Factory;

use App\DTO\Api\UserRegistrationDto;
use App\Entity\User;

interface UserRegistrationFactoryInterface
{
	/**
	 * @param UserRegistrationDto $userRegistrationDto
	 *
	 * @return User
	 */
	public function create(UserRegistrationDto $userRegistrationDto): User;
}