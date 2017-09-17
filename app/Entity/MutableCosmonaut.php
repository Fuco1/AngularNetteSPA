<?php declare(strict_types = 1);

namespace App\Entity;

use DateTimeImmutable;
use DateTimeInterface;
use JsonSerializable;


/**
 * This is a decorator which allows for updates of the wrapped
 * "immutable" Cosmonaut.  With this we can ensure we are only
 * updating the cosmonaut in places where we actually want this.
 *
 * The need to have a mutable representation comes from Doctrine which
 * can't really work with immutable data.
 */
class MutableCosmonaut extends Cosmonaut implements JsonSerializable
{

	/**
	 * @var Cosmonaut
	 */
	private $cosmonaut;


	public function __construct(Cosmonaut $cosmonaut) {
		$this->cosmonaut = $cosmonaut;
	}


	public function updateWith(Cosmonaut $newCosmonaut): void {
		$this->cosmonaut->name = $newCosmonaut->getName();
		$this->cosmonaut->surname = $newCosmonaut->getSurname();
		$this->cosmonaut->dateOfBirth = new DateTimeImmutable($newCosmonaut->getDateOfBirth()->format('Y-m-d'));
		$this->cosmonaut->superpower = $newCosmonaut->getSuperpower();
	}


	public function getName(): string {
		return $this->cosmonaut->name;
	}


	public function getSurname(): string {
		return $this->cosmonaut->surname;
	}


	public function getDateOfBirth(): DateTimeInterface {
		return new DateTimeImmutable($this->cosmonaut->dateOfBirth->format('Y-m-d'));
	}


	public function getSuperpower(): string {
		return $this->cosmonaut->superpower;
	}


	public function getCosmonaut(): Cosmonaut {
		return $this->cosmonaut;
	}

}
