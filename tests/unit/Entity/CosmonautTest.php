<?php declare(strict_types = 1);

namespace Tests\App\Entity;

use App\Entity;
use DateTime;
use DateTimeImmutable;
use Tester;
use Tester\Assert;

require_once __DIR__ . '/../../bootstrap.php';


/**
 * @testCase
 */
class CosmonautTest extends Tester\TestCase
{

	public function testJsonSerialize(): void {
		$cosmonaut = new Entity\Cosmonaut('Jon', 'Snow', new DateTimeImmutable('1999-01-02'), 'bastard');

		Assert::equal([
			'name' => 'Jon',
			'surname' => 'Snow',
			'dateOfBirth' => '1999-01-02',
			'superpower' => 'bastard',
		], $cosmonaut->jsonSerialize());
	}


	public function testReturnCopyOfDateOfBirth(): void {
		$cosmonaut = new Entity\Cosmonaut('Jon', 'Snow', new DateTime('1999-01-02'), 'bastard');

		$dateOfBirth = $cosmonaut->getDateOfBirth()->modify('+1 day');

		Assert::equal('1999-01-03', $dateOfBirth->format('Y-m-d'));
		Assert::equal('1999-01-02', $cosmonaut->getDateOfBirth()->format('Y-m-d'));
	}

}

(new CosmonautTest)->run();
