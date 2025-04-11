<?php

namespace App\Repository;

use App\Entity\Vehicle;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

class VehicleRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Vehicle::class);
    }

    /**
     * Récupère tous les véhicules triés par marque et modèle
     * @return Vehicle[]
     */
    public function findAllSorted(): array
    {
        return $this->createQueryBuilder('v')
            ->orderBy('v.brand', 'ASC')   // Tri par la marque de manière croissante
            ->addOrderBy('v.model', 'ASC') // Ensuite tri par modèle de manière croissante
            ->getQuery()
            ->getResult();
    }

    /**
     * Sauvegarde un véhicule en base de données
     */
    public function save(Vehicle $vehicle, bool $flush = true): void
    {
        $this->_em->persist($vehicle);
        if ($flush) {
            $this->_em->flush();
        }
    }

    /**
     * Supprime un véhicule de la base de données
     */
    public function remove(Vehicle $vehicle, bool $flush = true): void
    {
        $this->_em->remove($vehicle);
        if ($flush) {
            $this->_em->flush();
        }
    }

   
    /**
     * @return Vehicle[]
     */
    public function findByMaxPrice(float $maxPrice): array
    {
        return $this->createQueryBuilder('v')
            ->andWhere('v.dailyPrice <= :maxPrice')
            ->setParameter('maxPrice', $maxPrice)
            ->orderBy('v.dailyPrice', 'DESC')
            ->getQuery()
            ->getResult();
    }
}
