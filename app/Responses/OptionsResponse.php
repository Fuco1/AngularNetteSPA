<?php declare(strict_types = 1);

namespace App\Responses;

use Nette\Application;
use Nette\Http;


class OptionsResponse implements Application\IResponse
{

	public function send(Http\IRequest $httpRequest, Http\IResponse $httpResponse): void {
		$httpResponse->setCode(200);

		$httpResponse->setHeader('Access-Control-Allow-Origin', '*');
		$httpResponse->setHeader('Access-Control-Allow-Methods', 'GET, POST, PUT, DELETE, OPTIONS');
		$httpResponse->setHeader('Access-Control-Allow-Headers', 'Content-Type');
	}

}
