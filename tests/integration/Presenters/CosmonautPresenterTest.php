<?php declare(strict_types = 1);

namespace IntegrationTests\App\Presenters;

use App\Entity;
use App\Facade;
use App\Presenters;
use App\Responses\JsonResponse;
use DateTime;
use Kdyby;
use Mockery;
use Nette;
use Nette\Application\Request;
use Tester;
use Tester\Assert;

$container = require_once __DIR__ . '/../../bootstrap.php';


/**
 * @testCase
 */
class CosmonautPresenterTest extends Tester\TestCase
{

	/**
	 * @var Facade\Cosmonaut
	 */
	private $cosmonautFacade;


	/**
	 * @var Kdyby\Doctrine\EntityRepository
	 */
	private $cosmonautRepository;


	/**
	 * @var Kdyby\Doctrine\EntityManager
	 */
	private $entityManager;


	/**
	 * @var Nette\Application\LinkGenerator
	 */
	private $linkGenerator;


	/**
	 * @var Presenters\CosmonautPresenter
	 */
	private $presenter;


	public function __construct(Facade\Cosmonaut $cosmonautFacade, Kdyby\Doctrine\EntityManager $entityManager, Nette\Application\LinkGenerator $linkGenerator) {
		$this->cosmonautFacade = $cosmonautFacade;
		$this->entityManager = $entityManager;
		$this->cosmonautRepository = $entityManager->getRepository(Entity\Cosmonaut::class);
		$this->linkGenerator = $linkGenerator;
	}


	public function setUp(): void {
		lockDatabase();

		$this->presenter = new Presenters\CosmonautPresenter($this->cosmonautRepository, $this->cosmonautFacade);
		$this->presenter->linkGenerator = $this->linkGenerator;
	}


	public function tearDown(): void {
		$cosmonauts = $this->cosmonautRepository->findAll();
		$this->entityManager->remove($cosmonauts);
		$this->entityManager->flush();
	}


	public function testGetCosmonautValid(): void {
		$cosmonaut = new Entity\Cosmonaut('Jon', 'Snow', new DateTime('2000-10-10'), 'singing');
		$this->entityManager->persist($cosmonaut);
		$this->entityManager->flush();

		$response = $this->presenter->run(new Request('Cosmonaut:default', 'GET', ['id' => $cosmonaut->getId()]));

		Assert::equal(JsonResponse::HTTP_200_OK, $response->getCode());
	}


	public function testGetCosmonautNonexistent(): void {
		$response = $this->presenter->run(new Request('Cosmonaut:default', 'GET', ['id' => 666]));

		Assert::equal(JsonResponse::HTTP_404_NOT_FOUND, $response->getCode());
	}


	public function testDeleteCosmonautValid(): void {
		$cosmonaut = new Entity\Cosmonaut('Jon', 'Snow', new DateTime('2000-10-10'), 'singing');
		$this->entityManager->persist($cosmonaut);
		$this->entityManager->flush();

		$response = $this->presenter->run(new Request('Cosmonaut:default', 'DELETE', ['id' => $cosmonaut->getId()]));

		Assert::equal(JsonResponse::HTTP_204_NO_CONTENT, $response->getCode());
		Assert::false($this->entityManager->contains($cosmonaut));
	}


	public function testDeleteCosmonautNonexistent(): void {
		$response = $this->presenter->run(new Request('Cosmonaut:default', 'DELETE', ['id' => 666]));

		Assert::equal(JsonResponse::HTTP_404_NOT_FOUND, $response->getCode());
	}


	public function testPutCosmonautValid(): void {
		$cosmonaut = new Entity\Cosmonaut('Jon', 'Snow', new DateTime('2000-10-10'), 'singing');
		$this->entityManager->persist($cosmonaut);
		$this->entityManager->flush();

		$httpRequest = Mockery::mock(Nette\Http\Request::class);
		$httpRequest->shouldReceive('getRawBody')->andReturn(json_encode([
			'name' => 'John',
			'surname' => 'Doe',
			'dateOfBirth' => '2000-10-11',
			'superpower' => 'unknown',
		]));
		$httpRequest->shouldReceive('getHeader')->with('content-type')->andReturn('application/json');
		$this->presenter->httpRequest = $httpRequest;

		$response = $this->presenter->run(new Request('Cosmonaut:default', 'PUT', [
			'id' => $cosmonaut->getId(),
		]));

		Assert::equal(JsonResponse::HTTP_202_ACCEPTED, $response->getCode());
		Assert::truthy($response->getContentLocation());

		$updated = $response->getData();
		Assert::type(Entity\Cosmonaut::class, $updated);
		Assert::true($this->entityManager->contains($updated));
		Assert::equal($cosmonaut, $updated);
	}


	public function testPutCosmonautNonexistent(): void {
		$httpRequest = Mockery::mock(Nette\Http\Request::class);
		$httpRequest->shouldReceive('getHeader')->with('content-type')->andReturn('application/json');
		$httpRequest->shouldReceive('getRawBody')->andReturn("");
		$this->presenter->httpRequest = $httpRequest;

		$response = $this->presenter->run(new Request('Cosmonaut:default', 'PUT', ['id' => 666]));

		Assert::equal(JsonResponse::HTTP_404_NOT_FOUND, $response->getCode());
	}


	public function testPutCosmonautInvalid(): void {
		$cosmonaut = new Entity\Cosmonaut('Jon', 'Snow', new DateTime('2000-10-10'), 'singing');
		$this->entityManager->persist($cosmonaut);
		$this->entityManager->flush();

		$httpRequest = Mockery::mock(Nette\Http\Request::class);
		$httpRequest->shouldReceive('getRawBody')->andReturn(json_encode([
			'name' => 'John',
		]));
		$httpRequest->shouldReceive('getHeader')->with('content-type')->andReturn('application/json');
		$this->presenter->httpRequest = $httpRequest;

		$response = $this->presenter->run(new Request('Cosmonaut:default', 'PUT', [
			'id' => $cosmonaut->getId(),
		]));

		Assert::equal(JsonResponse::HTTP_422_UNPROCESSABLE_ENTITY, $response->getCode());
	}

}

(new CosmonautPresenterTest($container->getByType(Facade\Cosmonaut::class), $container->getByType(Kdyby\Doctrine\EntityManager::class), $container->getByType(Nette\Application\LinkGenerator::class)))->run();
