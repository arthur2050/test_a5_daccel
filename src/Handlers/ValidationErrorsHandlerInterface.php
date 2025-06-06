<?php

namespace App\Handlers;

use Symfony\Component\Validator\ConstraintViolationListInterface;

interface ValidationErrorsHandlerInterface
{
	/**
	 * @param ConstraintViolationListInterface $errors errors
	 *
	 * @return array
	 */
	public function format(ConstraintViolationListInterface $errors): array;
}