<?php declare(strict_types = 1);

namespace App\Presenters;

use App\Responses\JsonResponse;
use Nette\Application\Request;

class UserPresenter extends BasePresenter
{

	public function get(Request $request): JsonResponse
	{
		return new JsonResponse(['we' => 'good']);
	}



	/**
	 * @param mixed[] $data
	 */
	public function post(Request $request, array $data): JsonResponse
	{
		return new JsonResponse(['data' => $data]);
	}

}
