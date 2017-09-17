<?php declare(strict_types = 1);

namespace Tests\App\Entity;

use App\Entity;
use DateTime;
use Tester;
use Tester\Assert;

require_once __DIR__ . '/../../bootstrap.php';


/**
 * @testCase
 */
class MutableCosmonautTest extends Tester\TestCase
{

	public function testUpdateWithAnotherCosmonaut(): void {
		$date = new DateTime('1999-01-02');
		$cosmonaut = new Entity\Cosmonaut('Jon', 'Snow', $date, 'killing whitewalkers');
		$mutableCosmonaut = new Entity\MutableCosmonaut($cosmonaut);

		$date2 = new DateTime('1979-01-02');
		$cosmonaut2 = new Entity\Cosmonaut('Frank', 'Zappa', $date, 'singing');

		$mutableCosmonaut->updateWith($cosmonaut2);

		Assert::equal($cosmonaut2, $mutableCosmonaut->getCosmonaut());
		Assert::equal($cosmonaut2, $cosmonaut);
	}

}

(new MutableCosmonautTest)->run();
