<?php declare(strict_types = 1);

namespace IntegrationTests\App\Presenters;

use App\Presenters;
use Mockery;
use Nette;
use Nette\Application\Request;
use Tester;

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

		$response = $this->presenter->run(new Request('Cosmonaut:default', 'POST'));
	}


	/**
	 * @throws Nette\Application\BadRequestException Only application/json requests are accepted.
	 */
	public function testInvalidContentTypePut(): void {
		$httpRequest = Mockery::mock(Nette\Http\Request::class);
		$httpRequest->shouldReceive('getHeader')->with('content-type')->andReturn('text/html');
		$this->presenter->httpRequest = $httpRequest;

		$response = $this->presenter->run(new Request('Cosmonaut:default', 'PUT'));
	}


	/**
	 * @throws Nette\Application\BadRequestException Method 'get' not allowed.
	 */
	public function testMethodNotAllowed(): void {
		$this->presenter->run(new Request('Cosmonaut:default', 'GET'));
	}

}

(new BasePresenterTest())->run();
