<?php declare(strict_types = 1);

namespace App\Presenters;

use App\Responses\JsonResponse;
use Nette\Application\BadRequestException;
use Nette\Application\IPresenter;
use Nette\Application\IResponse;
use Nette\Application\Request;
use Throwable;
use Tracy\ILogger;


class ErrorPresenter implements IPresenter
{

	/** @var ILogger|NULL */
	protected $logger;

	public function __construct(?ILogger $logger = NULL)
	{
		$this->logger = $logger;
	}

	public function run(Request $request): IResponse
	{
		$e = $request->getParameter('exception');
		if ($e instanceof BadRequestException) {
			$code = $e->getCode();
		} else {
			$code = 500;
			if ($this->logger) {
				try {
					$this->logger->log($e, ILogger::EXCEPTION);
				} catch (Throwable $e) {
					// logger may fail as well
				}
			}
		}

		if (isset(JsonResponse::$messages[$code])) {
			$message = JsonResponse::$messages[$code];
		} else {
			$message = 'Unknown error';
		}

		return $this->createResponse($message, $code);
	}

	protected function createResponse(string $message, int $code): IResponse
	{
		return new JsonResponse(['message' => $message], $code);
	}

}
