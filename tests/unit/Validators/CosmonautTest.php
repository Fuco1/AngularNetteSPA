<?php declare(strict_types = 1);

namespace Tests\App\Validators;

use App\Validators;
use Tester;
use Tester\Assert;

$container = require_once __DIR__ . '/../../bootstrap.php';


/**
 * @testCase
 */
class CosmonautTest extends Tester\TestCase
{

	public function testValidateRemoveUselessAttributes(): void {
		$validated = Validators\Cosmonaut::validate([
			'name' => 'Jon',
			'surname' => 'Snow',
			'dateOfBirth' => '2010-10-10',
			'superpower' => 'foo',
			'extraField' => 'asdad',
		]);
		Assert::false(array_key_exists('extraField', $validated));
	}



	/**
	 * @throws Nette\Utils\AssertionException Missing item 'name' in array.
	 */
	public function testValidateMissingName(): void {
		Validators\Cosmonaut::validate([]);
	}


	/**
	 * @throws Nette\Utils\AssertionException Missing item 'surname' in array.
	 */
	public function testValidateMissingSurname(): void {
		Validators\Cosmonaut::validate(['name' => 'foo']);
	}


	/**
	 * @throws Nette\Utils\AssertionException Missing item 'dateOfBirth' in array.
	 */
	public function testValidateMissingDateOfBirth(): void {
		Validators\Cosmonaut::validate([
			'name' => 'foo',
			'surname' => 'bar',
		]);
	}


	/**
	 * @throws Nette\Utils\AssertionException Invalid date format, expected 'Y-m-d', got 'asdasd'.
	 */
	public function testInvalidDateOfBirth(): void {
		Validators\Cosmonaut::validate([
			'name' => 'foo',
			'surname' => 'bar',
			'dateOfBirth' => 'asdasd',
		]);
	}


	/**
	 * @throws Nette\Utils\AssertionException Missing item 'superpower' in array.
	 */
	public function testValidateMissingSuperpower(): void {
		Validators\Cosmonaut::validate([
			'name' => 'foo',
			'surname' => 'bar',
			'dateOfBirth' => '2000-10-10',
		]);
	}


	public function testValid(): void {
		$input = [
			'name' => 'foo',
			'surname' => 'bar',
			'dateOfBirth' => '2000-10-10',
			'superpower' => 'baz',
		];
		$validated = Validators\Cosmonaut::validate($input);

		Assert::type(Validators\Validated::class, $validated);
		foreach ($validated as $key => $value) {
			Assert::equal($input[$key], $value);
		}
	}

}

(new CosmonautTest())->run();
