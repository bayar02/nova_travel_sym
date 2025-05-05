<?php

namespace App\Repository;

use App\Entity\Planning;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Planning>
 */
class PlanningRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Planning::class);
    }

<<<<<<< HEAD
    //    /**
    //     * @return Planning[] Returns an array of Planning objects
    //     */
    //    public function findByExampleField($value): array
    //    {
    //        return $this->createQueryBuilder('p')
    //            ->andWhere('p.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->orderBy('p.id', 'ASC')
    //            ->setMaxResults(10)
    //            ->getQuery()
    //            ->getResult()
    //        ;
    //    }

    //    public function findOneBySomeField($value): ?Planning
    //    {
    //        return $this->createQueryBuilder('p')
    //            ->andWhere('p.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->getQuery()
    //            ->getOneOrNullResult()
    //        ;
    //    }
=======
    /**
     * Find all Planning entities.
     *
     * @return Planning[] Returns an array of Planning objects
     */
    public function findAll(): array
    {
        return $this->createQueryBuilder('p')
            ->orderBy('p.nom', 'ASC') // Sorting by name
            ->getQuery()
            ->getResult();
    }

    /**
     * Search plannings by name.
     *
     * @param string $name
     * @return Planning[] Returns an array of Planning objects
     */
    public function searchByName(string $name): array
    {
        return $this->createQueryBuilder('p')
            ->andWhere('p.nom LIKE :name')
            ->setParameter('name', '%' . $name . '%') // Partial match search
            ->orderBy('p.nom', 'ASC')
            ->getQuery()
            ->getResult();
    }

    /**
     * Get all plannings sorted by name.
     *
     * @param string $order ASC or DESC
     * @return Planning[] Returns an array of Planning objects
     */
    public function getSortedByName(string $order = 'ASC'): array
    {
        return $this->createQueryBuilder('p')
            ->orderBy('p.nom', $order)
            ->getQuery()
            ->getResult();
    }
>>>>>>> f5842df (Initial commit for Events branch)
}
