<?php

namespace App\Enum\Api;

enum TaskStatus: string
{
	case NEW = 'new';
	case IN_PROGRESS = 'in_progress';

	case CANCELLED = 'cancelled';
	case DONE = 'done';

	/**
	 * @return array
	 */
	public static function values(): array
	{
		return array_map(fn(self $status) => $status->value, self::cases());
	}
}