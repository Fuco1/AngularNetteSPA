<?php declare(strict_types = 1);

namespace IntegrationTests\App\Facade;

use App\Entity;
use App\Facade;
use Kdyby;
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


	/**
	 * @var Kdyby\Doctrine\EntityManager
	 */
	private $entityManager;


	public function __construct(Facade\Cosmonaut $cosmonautFacade, Kdyby\Doctrine\EntityManager $entityManager) {
		$this->cosmonautFacade = $cosmonautFacade;
		$this->entityManager = $entityManager;
	}


	public function setUp(): void {
		lockDatabase();
	}


	public function tearDown(): void {
		$cosmonauts = $this->entityManager->getRepository(Entity\Cosmonaut::class)->findAll();
		$this->entityManager->remove($cosmonauts);
		$this->entityManager->flush();
	}


	public function testSaveCosmonaut(): void {
		$cosmonaut = $this->cosmonautFacade->createCosmonaut([
			'name' => 'John',
			'surname' => 'Doe',
			'dateOfBirth' => '2000-10-10',
			'superpower' => 'unknown',
		]);
		$this->cosmonautFacade->saveCosmonaut($cosmonaut);

		$this->entityManager->contains($cosmonaut);
		$cosmonauts = $this->entityManager->getRepository(Entity\Cosmonaut::class)->findAll();

		Assert::count(1, $cosmonauts);

	}

}

(new CosmonautTest($container->getByType(Facade\Cosmonaut::class), $container->getByType(Kdyby\Doctrine\EntityManager::class)))->run();
