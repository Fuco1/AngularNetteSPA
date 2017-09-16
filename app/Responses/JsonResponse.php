<?php declare(strict_types = 1);

namespace App\Responses;

use JsonSerializable;
use Nette\Application;
use Nette\Http;
use Nette\Utils\Json;

class JsonResponse implements Application\IResponse
{

	public const HTTP_100_CONTINUE = 100;
	public const HTTP_101_SWITCHING_PROTOCOLS = 101;
	public const HTTP_102_PROCESSING = 102;
	public const HTTP_200_OK = 200;
	public const HTTP_201_CREATED = 201;
	public const HTTP_202_ACCEPTED = 202;
	public const HTTP_203_NON_AUTHORITATIVE_INFORMATION = 203;
	public const HTTP_204_NO_CONTENT = 204;
	public const HTTP_205_RESET_CONTENT = 205;
	public const HTTP_206_PARTIAL_CONTENT = 206;
	public const HTTP_207_MULTI_STATUS = 207;
	public const HTTP_300_MULTIPLE_CHOICES = 300;
	public const HTTP_301_MOVED_PERMANENTLY = 301;
	public const HTTP_302_FOUND = 302;
	public const HTTP_303_SEE_OTHER = 303;
	public const HTTP_304_NOT_MODIFIED = 304;
	public const HTTP_305_USE_PROXY = 305;
	public const HTTP_306_SWITCH_PROXY = 306;
	public const HTTP_307_TEMPORARY_REDIRECT = 307;
	public const HTTP_400_BAD_REQUEST = 400;
	public const HTTP_401_UNAUTHORIZED = 401;
	public const HTTP_402_PAYMENT_REQUIRED = 402;
	public const HTTP_403_FORBIDDEN = 403;
	public const HTTP_404_NOT_FOUND = 404;
	public const HTTP_405_METHOD_NOT_ALLOWED = 405;
	public const HTTP_406_NOT_ACCEPTABLE = 406;
	public const HTTP_407_PROXY_AUTHENTICATION_REQUIRED = 407;
	public const HTTP_408_REQUEST_TIMEOUT = 408;
	public const HTTP_409_CONFLICT = 409;
	public const HTTP_410_GONE = 410;
	public const HTTP_411_LENGTH_REQUIRED = 411;
	public const HTTP_412_PRECONDITION_FAILED = 412;
	public const HTTP_413_REQUEST_ENTITY_TOO_LARGE = 413;
	public const HTTP_414_REQUEST_URI_TOO_LONG = 414;
	public const HTTP_415_UNSUPPORTED_MEDIA_TYPE = 415;
	public const HTTP_416_REQUESTED_RANGE_NOT_SATISFIABLE = 416;
	public const HTTP_417_EXPECTATION_FAILED = 417;
	public const HTTP_418_IM_A_TEAPOT = 418;
	public const HTTP_422_UNPROCESSABLE_ENTITY = 422;
	public const HTTP_423_LOCKED = 423;
	public const HTTP_424_FAILED_DEPENDENCY = 424;
	public const HTTP_425_UNORDERED_COLLECTION = 425;
	public const HTTP_426_UPGRADE_REQUIRED = 426;
	public const HTTP_449_RETRY_WITH = 449;
	public const HTTP_450_BLOCKED_BY_WINDOWS_PARENTAL_CONTROLS = 450;
	public const HTTP_500_INTERNAL_SERVER_ERROR = 500;
	public const HTTP_501_NOT_IMPLEMENTED = 501;
	public const HTTP_502_BAD_GATEWAY = 502;
	public const HTTP_503_SERVICE_UNAVAILABLE = 503;
	public const HTTP_504_GATEWAY_TIMEOUT = 504;
	public const HTTP_505_HTTP_VERSION_NOT_SUPPORTED = 505;
	public const HTTP_506_VARIANT_ALSO_NEGOTIATES = 506;
	public const HTTP_507_INSUFFICIENT_STORAGE = 507;
	public const HTTP_509_BANDWIDTH_LIMIT_EXCEEDED = 509;
	public const HTTP_510_NOT_EXTENDED = 510;


	/**
	 * @var string[]
	 */
	public static $messages = [
		100 => 'Continue',
		101 => 'Switching Protocols',
		102 => 'Processing',
		200 => 'OK',
		201 => 'Created',
		202 => 'Accepted',
		203 => 'Non-Authoritative Information',
		204 => 'No Content',
		205 => 'Reset Content',
		206 => 'Partial Content',
		207 => 'Multi-Status',
		300 => 'Multiple Choices',
		301 => 'Moved Permanently',
		302 => 'Found',
		303 => 'See Other',
		304 => 'Not Modified',
		305 => 'Use Proxy',
		306 => 'Switch Proxy',
		307 => 'Temporary Redirect',
		400 => 'Bad Request',
		401 => 'Unauthorized',
		402 => 'Payment Required',
		403 => 'Forbidden',
		404 => 'Not Found',
		405 => 'Method Not Allowed',
		406 => 'Not Acceptable',
		407 => 'Proxy Authentication Required',
		408 => 'Request Timeout',
		409 => 'Conflict',
		410 => 'Gone',
		411 => 'Length Required',
		412 => 'Precondition Failed',
		413 => 'Request Entity Too Large',
		414 => 'Request-URI Too Long',
		415 => 'Unsupported Media Type',
		416 => 'Requested Range Not Satisfiable',
		417 => 'Expectation Failed',
		418 => 'I\'m a teapot',
		422 => 'Unprocessable Entity',
		423 => 'Locked',
		424 => 'Failed Dependency',
		425 => 'Unordered Collection',
		426 => 'Upgrade Required',
		449 => 'Retry With',
		450 => 'Blocked by Windows Parental Controls',
		500 => 'Internal Server Error',
		501 => 'Not Implemented',
		502 => 'Bad Gateway',
		503 => 'Service Unavailable',
		504 => 'Gateway Timeout',
		505 => 'HTTP Version Not Supported',
		506 => 'Variant Also Negotiates',
		507 => 'Insufficient Storage',
		509 => 'Bandwidth Limit Exceeded',
		510 => 'Not Extended',
	];


	/** @var mixed[]|JsonSerializable */
	private $data;


	/** @var int */
	private $code;


	/** @var string */
	private $contentType = 'application/json; charset=utf-8';


	/** @var ?int */
	private $expiration;


	/** @var ?string */
	private $contentLocation = null;


	/**
	 * @param mixed[]|JsonSerializable $data
	 */
	public function __construct($data, int $code = Http\IResponse::S200_OK, ?int $expiration = null) {
		$this->data = $data;
		$this->code = $code;
		$this->expiration = $expiration;
	}


	public function send(Http\IRequest $httpRequest, Http\IResponse $httpResponse): void {
		$httpResponse->setContentType($this->contentType);
		$httpResponse->setCode($this->code);
		$httpResponse->setExpiration($this->expiration);
		$httpResponse->setHeader('Pragma', $this->expiration ? 'cache': 'no-cache');
		if ($this->contentLocation) {
			$httpResponse->setHeader('Content-Location', $this->contentLocation);
		}

		echo Json::encode($this->data);
	}


	/**
	 * @return mixed[]|JsonSerializable
	 */
	public function getData() {
		return $this->data;
	}


	/**
	 * @param mixed[]|JsonSerializable $data
	 */
	public function setData($data): void {
		$this->data = $data;
	}


	public function getCode(): int {
		return $this->code;
	}


	public function setCode(int $code): void {
		$this->code = $code;
	}


	public function getContentType(): string {
		return $this->contentType;
	}


	public function setContentType(string $contentType): void {
		$this->contentType = $contentType;
	}


	public function setExpiration(?int $expiration): void {
		$this->expiration = $expiration;
	}


	public function getExpiration(): ?int {
		return $this->expiration;
	}


	public function setContentLocation(string $contentLocation): void {
		$this->contentLocation = $contentLocation;
	}


	public function getContentLocation(): ?string {
		return $this->contentLocation;
	}

}
