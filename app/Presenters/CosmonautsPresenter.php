<?php declare(strict_types = 1);

namespace App\Presenters;

use App\Facade;
use App\Responses\JsonResponse;
use InvalidArgumentException;
use Kdyby;
use Nette\Application\Request;

class CosmonautsPresenter extends BasePresenter
{

	private const ALLOWED_METHODS = ['get', 'post'];

	/**
	 * @var Facade\Cosmonaut
	 */
	private $cosmonautFacade;


	/**
	 * @var Kdyby\Doctrine\EntityRepository
	 */
	private $cosmonautRepository;


	public function __construct(Kdyby\Doctrine\EntityRepository $cosmonautRepository, Facade\Cosmonaut $cosmonautFacade) {
		$this->cosmonautRepository = $cosmonautRepository;
		$this->cosmonautFacade = $cosmonautFacade;

		$this->setAllowedMethods(self::ALLOWED_METHODS);
	}


	public function get(Request $request): JsonResponse {
		$cosmonauts = $this->cosmonautRepository->findAll();
		return new JsonResponse($cosmonauts);
	}


	/**
	 * @param mixed[] $data
	 */
	public function post(Request $request, array $data): JsonResponse {
		try {
			$cosmonaut = $this->cosmonautFacade->createCosmonaut($data);
		} catch (InvalidArgumentException $e) {
			return $this->sendErrorResponse(sprintf("Validation error: %s", $e->getMessage()), JsonResponse::HTTP_422_UNPROCESSABLE_ENTITY);
		}

		$this->cosmonautFacade->saveCosmonaut($cosmonaut);

		$response = new JsonResponse($cosmonaut, JsonResponse::HTTP_201_CREATED);
		$response->setContentLocation($this->getLinkGenerator()->link('Cosmonaut:default',  [
			'id' => $cosmonaut->getId(),
		]));
		return $response;
	}

}
