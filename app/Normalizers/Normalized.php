<?php declare(strict_types = 1);

namespace App\Normalizers;

use App\Validators;
use ArrayAccess;
use RuntimeException;


/**
 * This class represents normalized data from validated external
 * sources.  They are safe to be passed around the system and used
 * without further checks.
 *
 * To ensure this works as expected only classes inside
 * App\Normalizers should create instances of this class!
 */
class Normalized implements ArrayAccess
{

	/** @var mixed[] */
	private $data;


	/**
	 * @param mixed[] $data
	 */
	public function __construct(Validators\Validated $data) {
		$this->data = $data->getData();
	}


	/**
	 * @return mixed[]
	 */
	public function getData(): array {
		return $this->data;
	}


	/**
	 * @param mixed $offset
	 */
	public function offsetExists($offset): bool {
		return isset($this->data[$offset]);
	}


	/**
	 * @param mixed $offset
	 * @return mixed
	 */
	public function offsetGet($offset) {
		return $this->data[$offset];
	}


	/**
	 * @param mixed $offset
	 */
	public function offsetSet($offset, $value): void {
		throw new RuntimeException('This object is immutable.');
	}


	/**
	 * @param mixed $offset
	 */
	public function offsetUnset($offset): void {
		throw new RuntimeException('This object is immutable.');
	}

}
