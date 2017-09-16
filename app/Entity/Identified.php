<?php declare(strict_types = 1);

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\MappedSuperclass()
 */
class Identified
{

	/**
	 * @ORM\Id
	 * @ORM\Column(type = "integer", options = {"unsigned" = true})
	 * @ORM\GeneratedValue
	 * @var ?int
	 */
	private $id;


	public function getId(): ?int
	{
		return $this->id;
	}

}
