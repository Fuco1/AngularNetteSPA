<?php declare(strict_types = 1);

namespace App\Presenters;

use App\Facade;
use App\Responses\JsonResponse;
use InvalidArgumentException;
use Kdyby;
use Nette\Application\Request;

class CosmonautPresenter extends BasePresenter
{

	private const ALLOWED_METHODS = ['get', 'put', 'delete'];

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
		$cosmonautId = (int) $request->getParameter('id');

		$cosmonaut = $this->cosmonautRepository->find($cosmonautId);
		if (!$cosmonaut) {
			return $this->sendErrorResponse(sprintf("Cosmonaut identified by '%d' not found.", $cosmonautId), JsonResponse::HTTP_404_NOT_FOUND);
		}

		return new JsonResponse($cosmonaut);
	}


	public function delete(Request $request): JsonResponse {
		$cosmonautId = (int) $request->getParameter('id');

		$cosmonaut = $this->cosmonautRepository->find($cosmonautId);
		if (!$cosmonaut) {
			return $this->sendErrorResponse(sprintf("Cosmonaut identified by '%d' not found.", $cosmonautId), JsonResponse::HTTP_404_NOT_FOUND);
		}

		$this->cosmonautFacade->deleteCosmonaut($cosmonaut);

		return new JsonResponse("", JsonResponse::HTTP_204_NO_CONTENT);
	}


	/**
	 * @param mixed[] $data
	 */
	public function put(Request $request, array $data): JsonResponse {
		$cosmonautId = (int) $request->getParameter('id');

		$oldCosmonaut = $this->cosmonautRepository->find($cosmonautId);
		if (!$oldCosmonaut) {
			return $this->sendErrorResponse(sprintf("Cosmonaut identified by '%d' not found.", $cosmonautId), JsonResponse::HTTP_404_NOT_FOUND);
		}

		try {
			$newCosmonaut = $this->cosmonautFacade->createCosmonaut($data);
		} catch (InvalidArgumentException $e) {
			return $this->sendErrorResponse(sprintf("Validation error: %s", $e->getMessage()), JsonResponse::HTTP_422_UNPROCESSABLE_ENTITY);
		}

		$cosmonaut = $this->cosmonautFacade->updateCosmonaut($oldCosmonaut, $newCosmonaut);

		$response = new JsonResponse($cosmonaut, JsonResponse::HTTP_202_ACCEPTED);
		$response->setContentLocation($this->getLinkGenerator()->link('Cosmonaut:default',  [
			'id' => $cosmonaut->getId(),
		]));
		return $response;
	}

}
