<?php declare(strict_types = 1);

namespace App\Validators;

use DateTime;
use Nette;
use Nette\Utils\Validators;


class Cosmonaut
{

	private const ALLOWED_KEYS = ['name', 'surname', 'dateOfBirth', 'superpower'];


	/**
	 * @param mixed[] $cosmonaut
	 */
	public static function validate(array $cosmonaut): Validated
	{
		Validators::assert($cosmonaut, 'array', 'cosmonaut');

		Validators::assertField($cosmonaut, 'name', 'string');
		Validators::assertField($cosmonaut, 'surname', 'string');
		Validators::assertField($cosmonaut, 'dateOfBirth', 'string');
		if (DateTime::createFromFormat('Y-m-d', $cosmonaut['dateOfBirth']) === false) {
			throw new Nette\Utils\AssertionException(sprintf("Invalid date format, expected 'Y-m-d', got '%s'.", $cosmonaut['dateOfBirth']));
		}
		Validators::assertField($cosmonaut, 'superpower', 'string');

		$filteredCosmonaut = [];

		foreach (self::ALLOWED_KEYS as $key) {
			$filteredCosmonaut[$key] = $cosmonaut[$key];
		}

		return new Validated($filteredCosmonaut);
	}

}
