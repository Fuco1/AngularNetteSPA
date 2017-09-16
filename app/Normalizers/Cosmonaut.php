<?php declare(strict_types = 1);

namespace App\Normalizers;

use App\Validators\Validated;
use DateTimeImmutable;


class Cosmonaut
{

	/**
	 * @return mixed[]
	 */
	public static function normalize(Validated $cosmonaut): Normalized
	{
		$cosmonaut = $cosmonaut->getData();
		$cosmonaut['dateOfBirth'] = new DateTimeImmutable($cosmonaut['dateOfBirth']);

		return new Normalized(new Validated($cosmonaut));
	}

}
