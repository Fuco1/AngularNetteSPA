<?php declare(strict_types = 1);

namespace Tests\App\Facade;

use App\Entity;
use App\Facade;
use InvalidArgumentException;
use Tester;
use Tester\Assert;

$container = require_once __DIR__ . '/../../bootstrap.php';


/**
 * @testCase
 */
class CosmonautTest extends Tester\TestCase
{

	/**
	 * @var Facade\Cosmonaut
	 */
	private $cosmonautFacade;


	public function __construct(Facade\Cosmonaut $cosmonautFacade) {
		$this->cosmonautFacade = $cosmonautFacade;
	}


	/**
	 * @throws InvalidArgumentException Missing item 'dateOfBirth' in array.
	 */
	public function testCreateCosmonautMissingField(): void {
		$this->cosmonautFacade->createCosmonaut([
			'name' => 'John',
			'surname' => 'Doe',
		]);
	}


	/**
	 * @throws InvalidArgumentException The item 'name' in array expects to be string, integer given.
	 */
	public function testCreateCosmonautInvalidType(): void {
		$this->cosmonautFacade->createCosmonaut([
			'name' => 1,
		]);
	}


	public function testCreateCosmonautValid(): void {
		$cosmonaut = $this->cosmonautFacade->createCosmonaut([
			'name' => 'John',
			'surname' => 'Doe',
			'dateOfBirth' => '2000-10-10',
			'superpower' => 'unknown',
		]);

		Assert::type(Entity\Cosmonaut::class, $cosmonaut);
		Assert::equal('John', $cosmonaut->getName());
		Assert::equal('Doe', $cosmonaut->getSurname());
		Assert::equal('2000-10-10', $cosmonaut->getDateOfBirth()->format('Y-m-d'));
		Assert::equal('unknown', $cosmonaut->getSuperpower());
	}

}

(new CosmonautTest($container->getByType(Facade\Cosmonaut::class)))->run();
