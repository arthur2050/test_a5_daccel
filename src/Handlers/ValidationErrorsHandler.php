<?php

namespace App\Handlers;
use Symfony\Component\Validator\ConstraintViolationListInterface;

class ValidationErrorsHandler implements ValidationErrorsHandlerInterface
{
	/**
	 * @param ConstraintViolationListInterface $errors errors
	 *
	 * @return array
	 */
	public function format(ConstraintViolationListInterface $errors): array
	{
		$violations = [];

		foreach ($errors as $error) {
			$violations[] = [
				'propertyPath' => $error->getPropertyPath(),
				'message' => $error->getMessage(),
			];
		}

		return [
			'title' => 'Validation Failed',
			'status' => 422,
			'violations' => $violations,
		];
	}
}