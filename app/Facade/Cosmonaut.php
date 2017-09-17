<?php declare(strict_types = 1);

namespace App\Facade;

use App\Entity;
use App\Normalizers;
use App\Validators;
use InvalidArgumentException;
use Kdyby;
use Throwable;


class Cosmonaut
{

	/**
	 * @var Kdyby\Doctrine\EntityRepository
	 */
	private $cosmonautRepository;


	public function __construct(Kdyby\Doctrine\EntityRepository $cosmonautRepository) {
		$this->cosmonautRepository = $cosmonautRepository;
	}


	/**
	 * @param mixed[] $cosmonaut
	 * @throws InvalidArgumentException
	 */
	public function createCosmonaut(array $cosmonaut): Entity\Cosmonaut {
		try {
			$cosmonaut = Validators\Cosmonaut::validate($cosmonaut);
		} catch (Throwable $e) {
			throw new InvalidArgumentException($e->getMessage());
		}

		$cosmonaut = Normalizers\Cosmonaut::normalize($cosmonaut);

		return new Entity\Cosmonaut($cosmonaut['name'], $cosmonaut['surname'], $cosmonaut['dateOfBirth'], $cosmonaut['superpower']);
	}


	public function saveCosmonaut(Entity\Cosmonaut $cosmonaut): void {
		$em = $this->cosmonautRepository->getEntityManager();
		$em->persist($cosmonaut);
		$em->flush($cosmonaut);
	}


	public function deleteCosmonaut(Entity\Cosmonaut $cosmonaut): void {
		$em = $this->cosmonautRepository->getEntityManager();
		$em->remove($cosmonaut);
		$em->flush($cosmonaut);
	}


	public function updateCosmonaut(Entity\Cosmonaut $oldCosmonaut, Entity\Cosmonaut $newCosmonaut): Entity\Cosmonaut {
		$oldCosmonaut->updateWith($newCosmonaut);
		$this->saveCosmonaut($oldCosmonaut);
		return $oldCosmonaut;
	}

}
