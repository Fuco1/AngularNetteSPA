<?php declare(strict_types = 1);

namespace Tests\App\Normalizers;

use App\Normalizers;
use App\Validators\Validated;
use RuntimeException;
use Tester;
use Tester\Assert;

$container = require_once __DIR__ . '/../../bootstrap.php';


/**
 * @testCase
 */
class NormalizedTest extends Tester\TestCase
{

	/**
	 * @throws TypeError
	 */
	public function testMustCreateFromValidatedData(): void {
		new Normalizers\Normalized(['a' => 'b']);
	}


	public function testNormalizedIsValidated(): void {
		$normalized = new Normalizers\Normalized(new Validated(['a' => 'b']));
		Assert::type(Validated::class, $normalized);
	}


	/**
	 * @throws RuntimeException This object is immutable.
	 */
	public function testNoUnset(): void {
		$normalized = new Normalizers\Normalized(new Validated(['a' => 'b']));
		unset($normalized['a']);
	}


	/**
	 * @throws RuntimeException This object is immutable.
	 */
	public function testNoSet(): void {
		$normalized = new Normalizers\Normalized(new Validated(['a' => 'b']));
		$normalized['a'] = 'new';
	}


	public function testGet(): void {
		$normalized = new Normalizers\Normalized(new Validated(['a' => 'b']));
		Assert::equal('b', $normalized['a']);
	}


	public function testIsset(): void {
		$normalized = new Normalizers\Normalized(new Validated(['a' => 'b']));
		Assert::true(isset($normalized['a']));
		Assert::false(isset($normalized['b']));
	}

}

(new NormalizedTest())->run();
