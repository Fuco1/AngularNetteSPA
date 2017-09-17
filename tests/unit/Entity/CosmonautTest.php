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
			'id' => null,
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


	public function testSaveCopyOfDateOfBirth(): void {
		$date = new DateTime('1999-01-02');
		$cosmonaut = new Entity\Cosmonaut('Jon', 'Snow', $date, 'bastard');

		$date->modify('+1 day');
		$dateOfBirth = $cosmonaut->getDateOfBirth();

		Assert::equal('1999-01-02', $dateOfBirth->format('Y-m-d'));
		Assert::equal('1999-01-03', $date->format('Y-m-d'));
	}


	public function testUpdateWithAnotherCosmonaut(): void {
		$date = new DateTime('1999-01-02');
		$cosmonaut = new Entity\Cosmonaut('Jon', 'Snow', $date, 'killing whitewalkers');
		$date2 = new DateTime('1979-01-02');
		$cosmonaut2 = new Entity\Cosmonaut('Frank', 'Zappa', $date, 'singing');

		$cosmonaut->updateWith($cosmonaut2);

		Assert::equal($cosmonaut2, $cosmonaut);
	}

}

(new CosmonautTest)->run();
