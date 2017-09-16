<?php declare(strict_types = 1);

namespace App\Entity;

use DateTimeImmutable;
use DateTimeInterface;
use Doctrine\ORM\Mapping as ORM;
use JsonSerializable;


/**
 * @ORM\Entity
 */
class Cosmonaut extends Identified implements JsonSerializable
{

	/**
	 * @ORM\Column(type = "string")
	 * @var string
	 */
	private $name;


	/**
	 * @ORM\Column(type = "string")
	 * @var string
	 */
	private $surname;


	/**
	 * @ORM\Column(type = "date")
	 * @var DateTimeInterface
	 */
	private $dateOfBirth;


	/**
	 * @ORM\Column(type = "string")
	 * @var string
	 */
	private $superpower;


	public function __construct(string $name, string $surname, DateTimeInterface $dateOfBirth, string $superpower) {
		$this->name = $name;
		$this->surname = $surname;
		$this->dateOfBirth = $dateOfBirth;
		$this->superpower = $superpower;
	}


	public function getName(): string {
		return $this->name;
	}


	public function getSurname(): string {
		return $this->surname;
	}


	public function getDateOfBirth(): DateTimeInterface {
		return new DateTimeImmutable($this->dateOfBirth->format('Y-m-d'));
	}


	public function getSuperpower(): string {
		return $this->superpower;
	}


	/**
	 * @return mixed[]
	 */
	public function jsonSerialize(): array {
		return [
			'name' => $this->getName(),
			'surname' => $this->getSurname(),
			'dateOfBirth' => $this->getDateOfBirth()->format('Y-m-d'),
			'superpower' => $this->getSuperpower(),
		];
	}

}
