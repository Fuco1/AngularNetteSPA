<?php declare(strict_types = 1);

namespace App\Presenters;

use Nette\Application\BadRequestException;
use Nette\Application\IPresenter;
use Nette\Application\IResponse;
use Nette\Application\Request;
use Nette\Http;
use Nette\InvalidStateException;


abstract class BasePresenter implements IPresenter
{

    /**
     * @inject
     * @var Http\Request
     */
    private $httpRequest;


    public function getHttpRequest(): Http\Request
    {
        return $this->httpRequest;
    }

    public function run(Request $request): IResponse
    {
        $method = strtolower($request->getMethod());
        if (!method_exists($this, $method)) {
            throw new BadRequestException("Method '{$request->getMethod()}' not supported.");
        }

        $response = $this->$method($request);
        if (!$response instanceof IResponse) {
            throw new InvalidStateException("Presenter '{$request->getPresenterName()}' did not return any response for method '{$request->getMethod()}'.");
        }
        return $response;
    }

}
