<?php declare(strict_types = 1);

namespace App\Presenters;

use App\Responses\JsonResponse;
use Nette;
use Nette\Application\BadRequestException;
use Nette\Application\IPresenter;
use Nette\Application\IResponse;
use Nette\Application\Request;
use Nette\Http;
use Nette\InvalidStateException;
use Nette\Utils\Json;


abstract class BasePresenter implements IPresenter
{

    /**
     * @inject
     * @var Http\Request
     */
    public $httpRequest;


	/**
	 * @inject
	 * @var Nette\Application\LinkGenerator
	 */
	public $linkGenerator;


    public function getHttpRequest(): Http\Request {
        return $this->httpRequest;
    }


	public function getLinkGenerator(): Nette\Application\LinkGenerator {
		return $this->linkGenerator;
	}


    /**
     * @throws BadRequestException
     */
    public function run(Request $request): IResponse {
        $method = strtolower($request->getMethod());

		$data = [];
        if ($method !== 'get') {
            $contentType = $this->httpRequest->getHeader('content-type');
            if ($contentType !== 'application/json') {
                throw new BadRequestException('Only application/json requests are accepted.');
            }
            $body = trim($this->httpRequest->getRawBody());
			if (!empty($body)) {
				$data = Json::decode($body, Json::FORCE_ARRAY);
			}
        }


        if (!method_exists($this, $method)) {
            throw new BadRequestException("Method '{$request->getMethod()}' not supported.");
        }

        $response = $this->$method($request, $data);
        if (!$response instanceof IResponse) {
            throw new InvalidStateException("Presenter '{$request->getPresenterName()}' did not return any response for method '{$request->getMethod()}'.");
        }
        return $response;
    }


	protected function sendErrorResponse(string $message, int $code): JsonResponse {
		return new JsonResponse(['error' => $message], $code);
	}

}
