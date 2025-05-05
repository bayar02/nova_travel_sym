<?php

namespace App\Repository;

use App\Entity\ReservationVol;
<<<<<<< HEAD
use App\Entity\User;
=======
>>>>>>> f5842df (Initial commit for Events branch)
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<ReservationVol>
 */
class ReservationVolRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, ReservationVol::class);
    }

<<<<<<< HEAD
    /**
     * Find all flight reservations for a specific user with flight details
     * 
     * @param User $user The user to find reservations for
     * @return ReservationVol[] Returns an array of ReservationVol objects with joined flight data
     */
    public function findByUserWithFlightDetails(User $user): array
    {
        return $this->createQueryBuilder('r')
            ->andWhere('r.user = :user')
            ->setParameter('user', $user)
            ->innerJoin('r.vol', 'v')
            ->addSelect('v')
            ->orderBy('v.date_depart', 'ASC')
            ->getQuery()
            ->getResult();
    }

=======
>>>>>>> f5842df (Initial commit for Events branch)
    //    /**
    //     * @return ReservationVol[] Returns an array of ReservationVol objects
    //     */
    //    public function findByExampleField($value): array
    //    {
    //        return $this->createQueryBuilder('r')
    //            ->andWhere('r.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->orderBy('r.id', 'ASC')
    //            ->setMaxResults(10)
    //            ->getQuery()
    //            ->getResult()
    //        ;
    //    }

    //    public function findOneBySomeField($value): ?ReservationVol
    //    {
    //        return $this->createQueryBuilder('r')
    //            ->andWhere('r.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->getQuery()
    //            ->getOneOrNullResult()
    //        ;
    //    }
}
