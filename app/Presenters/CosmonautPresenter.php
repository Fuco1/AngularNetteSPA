<?php declare(strict_types = 1);

namespace App\Presenters;

use App\Facade;
use App\Responses\JsonResponse;
use InvalidArgumentException;
use Kdyby;
use Nette\Application\Request;

class CosmonautPresenter extends BasePresenter
{

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
	}


	public function get(Request $request): JsonResponse {
		$cosmonautId = (int) $request->getParameter('id');

		$cosmonaut = $this->cosmonautRepository->find($cosmonautId);
		if (!$cosmonaut) {
			return $this->sendErrorResponse(sprintf("Cosmonaut identified by '%d' not found.", $cosmonautId), JsonResponse::HTTP_404_NOT_FOUND);
		}

		return new JsonResponse($cosmonaut);
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

		$response = new JsonResponse($cosmonaut);
		$response->setContentLocation($this->getLinkGenerator()->link('Cosmonaut:default',  [
			'id' => $cosmonaut->getId(),
		]));
		return $response;
	}

}
