<?php declare(strict_types = 1);

namespace App\Presenters;

use App\Responses\JsonResponse;
use App\Responses\OptionsResponse;
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


	/**
	 * @var string[]
	 */
	private $allowedMethods = [];


    public function getHttpRequest(): Http\Request {
        return $this->httpRequest;
    }


	public function getLinkGenerator(): Nette\Application\LinkGenerator {
		return $this->linkGenerator;
	}


	/**
	 * @param string[] $allowedMethods
	 */
	protected function setAllowedMethods(array $allowedMethods): void {
		$this->allowedMethods = $allowedMethods;
	}


    /**
     * @throws BadRequestException
     */
    public function run(Request $request): IResponse {
        $method = $this->getMethod($request);

		if (!in_array($method, array_merge(['options'], $this->allowedMethods))) {
			throw new BadRequestException(sprintf("Method '%s' not allowed.", $method));
		}


		$data = [];
        if (in_array($method, ['post', 'put'], true)) {
            $contentType = $this->httpRequest->getHeader('content-type');
            if (strstr($contentType, 'application/json') === false) {
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


	protected function options(Request $request): IResponse {
		return new OptionsResponse();
	}


	protected function getMethod(Request $request): string {
		return strtolower($request->getMethod());
	}


	protected function sendErrorResponse(string $message, int $code): JsonResponse {
		return new JsonResponse(['error' => $message], $code);
	}

}
