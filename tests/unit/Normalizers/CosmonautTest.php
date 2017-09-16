<?php declare(strict_types = 1);

namespace Tests\App\Normalizers;

use App\Normalizers;
use App\Validators;
use DateTimeInterface;
use Tester;
use Tester\Assert;

$container = require_once __DIR__ . '/../../bootstrap.php';


/**
 * @testCase
 */
class CosmonautTest extends Tester\TestCase
{

	public function testNormalizeDate(): void {
		$normalized = Normalizers\Cosmonaut::normalize(new Validators\Validated([
			'name' => 'Jon',
			'surname' => 'Snow',
			'dateOfBirth' => '2010-10-10',
			'superpower' => 'foo',
		]));
		Assert::type(DateTimeInterface::class, $normalized['dateOfBirth']);
		Assert::equal('2010-10-10', $normalized['dateOfBirth']->format('Y-m-d'));
	}

}

(new CosmonautTest())->run();
