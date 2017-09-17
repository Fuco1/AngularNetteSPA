<?php declare(strict_types = 1);

namespace App\Normalizers;

use App\Validators;


/**
 * This class represents normalized data from validated external
 * sources.  They are safe to be passed around the system and used
 * without further checks.
 *
 * To ensure this works as expected only classes inside
 * App\Normalizers should create instances of this class!
 */
class Normalized extends Validators\Validated
{

	/**
	 * @param mixed[] $data
	 */
	public function __construct(Validators\Validated $data) {
		parent::__construct($data->getData());
	}

}
