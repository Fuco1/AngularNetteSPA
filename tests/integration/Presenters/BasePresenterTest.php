<?php declare(strict_types = 1);

namespace IntegrationTests\App\Presenters;

use App\Presenters;
use App\Responses\JsonResponse;
use Mockery;
use Nette;
use Nette\Application\Request;
use Tester;
use Tester\Assert;

$container = require_once __DIR__ . '/../../bootstrap.php';


/**
 * @testCase
 */
class BasePresenterTest extends Tester\TestCase
{

	/**
	 * @var Presenters\BasePresenter
	 */
	private $presenter;


	public function setUp(): void {
		$this->presenter = new class() extends Presenters\BasePresenter {
				public function __construct() {
					$this->setAllowedMethods(['post', 'put']);
				}
			};
	}

	/**
	 * @throws Nette\Application\BadRequestException Only application/json requests are accepted.
	 */
	public function testInvalidContentTypePost(): void {
		$httpRequest = Mockery::mock(Nette\Http\Request::class);
		$httpRequest->shouldReceive('getHeader')->with('content-type')->andReturn('text/html');
		$this->presenter->httpRequest = $httpRequest;

		try {
			$this->presenter->run(new Request('Cosmonaut:default', 'POST'));
			Assert::fail('Must throw');
		} catch (Nette\Application\BadRequestException $e) {
			Assert::equal(JsonResponse::HTTP_415_UNSUPPORTED_MEDIA_TYPE, $e->getCode());
			throw $e;
		}
	}


	/**
	 * @throws Nette\Application\BadRequestException Only application/json requests are accepted.
	 */
	public function testInvalidContentTypePut(): void {
		$httpRequest = Mockery::mock(Nette\Http\Request::class);
		$httpRequest->shouldReceive('getHeader')->with('content-type')->andReturn('text/html');
		$this->presenter->httpRequest = $httpRequest;

		try {
			$this->presenter->run(new Request('Cosmonaut:default', 'PUT'));
			Assert::fail('Must throw');
		} catch (Nette\Application\BadRequestException $e) {
			Assert::equal(JsonResponse::HTTP_415_UNSUPPORTED_MEDIA_TYPE, $e->getCode());
			throw $e;
		}
	}


	/**
	 * @throws Nette\Application\BadRequestException Method 'get' not allowed.
	 */
	public function testMethodNotAllowed(): void {
		try {
			$this->presenter->run(new Request('Cosmonaut:default', 'GET'));
			Assert::fail('Must throw');
		} catch (Nette\Application\BadRequestException $e) {
			Assert::equal(JsonResponse::HTTP_405_METHOD_NOT_ALLOWED, $e->getCode());
			throw $e;
		}
	}


	/**
	 * @throws Nette\Application\BadRequestException Method 'FOO' not supported.
	 */
	public function testMethodNotSupported(): void {
		$this->presenter = new class() extends Presenters\BasePresenter {
				public function __construct() {
					$this->setAllowedMethods(['foo']);
				}
			};

		try {
			$this->presenter->run(new Request('Cosmonaut:default', 'FOO'));
			Assert::fail('Must throw');
		} catch (Nette\Application\BadRequestException $e) {
			Assert::equal(JsonResponse::HTTP_501_NOT_IMPLEMENTED, $e->getCode());
			throw $e;
		}
	}

}

(new BasePresenterTest())->run();
