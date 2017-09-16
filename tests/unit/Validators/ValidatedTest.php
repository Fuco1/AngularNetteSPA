<?php declare(strict_types = 1);

namespace Tests\App\Validators;

use App\Validators;
use RuntimeException;
use Tester;
use Tester\Assert;

$container = require_once __DIR__ . '/../../bootstrap.php';


/**
 * @testCase
 */
class ValidatedTest extends Tester\TestCase
{

	/**
	 * @throws RuntimeException This object is immutable.
	 */
	public function testNoUnset(): void {
		$validated = new Validators\Validated(['a' => 'b']);
		unset($validated['a']);
	}


	/**
	 * @throws RuntimeException This object is immutable.
	 */
	public function testNoSet(): void {
		$validated = new Validators\Validated(['a' => 'b']);
		$validated['a'] = 'new';
	}


	public function testGet(): void {
		$validated = new Validators\Validated(['a' => 'b']);
		Assert::equal('b', $validated['a']);
	}


	public function testIsset(): void {
		$validated = new Validators\Validated(['a' => 'b']);
		Assert::true(isset($validated['a']));
		Assert::false(isset($validated['b']));
	}

}

(new ValidatedTest())->run();
