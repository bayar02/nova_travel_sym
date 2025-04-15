<?php

namespace App\Repository;

use App\Entity\Vol;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Vol>
 */
class VolRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Vol::class);
    }

    /**
     * Find upcoming flights (departure date in the future)
     * 
     * @param int $limit The maximum number of results to retrieve
     * @return Vol[] Returns an array of upcoming Vol objects
     */
    public function findUpcoming(int $limit = 5): array
    {
        $now = new \DateTime();
        
        return $this->createQueryBuilder('v')
            ->andWhere('v.date_depart > :now')
            ->setParameter('now', $now)
            ->orderBy('v.date_depart', 'ASC')
            ->setMaxResults($limit)
            ->getQuery()
            ->getResult();
    }

    /**
     * Search flights by criteria
     * 
     * @param array $criteria Search criteria (destination, dateStart, dateEnd)
     * @param string $sortBy Field to sort by (date, price_asc, price_desc)
     * @return Vol[] Returns an array of matching Vol objects
     */
    public function searchFlights(array $criteria, string $sortBy = 'date'): array
    {
        $queryBuilder = $this->createQueryBuilder('v');
        
        // Base query
        $queryBuilder->where('1=1');
        
        // Filter by destination if specified
        if (!empty($criteria['destination'])) {
            $queryBuilder
                ->andWhere('LOWER(v.destination) LIKE LOWER(:destination)')
                ->setParameter('destination', '%' . $criteria['destination'] . '%');
        }
        
        // Filter by date range if specified
        if (!empty($criteria['dateStart']) && !empty($criteria['dateEnd'])) {
            $startDate = new \DateTime($criteria['dateStart']);
            $endDate = new \DateTime($criteria['dateEnd']);
            
            $queryBuilder
                ->andWhere(
                    $queryBuilder->expr()->orX(
                        // Departure date within range
                        $queryBuilder->expr()->andX(
                            $queryBuilder->expr()->gte('v.date_depart', ':startDate'),
                            $queryBuilder->expr()->lte('v.date_depart', ':endDate')
                        ),
                        // Arrival date within range
                        $queryBuilder->expr()->andX(
                            $queryBuilder->expr()->gte('v.date_arrivee', ':startDate'),
                            $queryBuilder->expr()->lte('v.date_arrivee', ':endDate')
                        )
                    )
                )
                ->setParameter('startDate', $startDate)
                ->setParameter('endDate', $endDate);
        }
        
        // Apply sorting
        switch ($sortBy) {
            case 'price_asc':
                $queryBuilder->orderBy('v.prix', 'ASC');
                break;
            case 'price_desc':
                $queryBuilder->orderBy('v.prix', 'DESC');
                break;
            case 'date':
            default:
                $queryBuilder->orderBy('v.date_depart', 'ASC');
                break;
        }
        
        return $queryBuilder
            ->getQuery()
            ->getResult();
    }

    //    /**
    //     * @return Vol[] Returns an array of Vol objects
    //     */
    //    public function findByExampleField($value): array
    //    {
    //        return $this->createQueryBuilder('v')
    //            ->andWhere('v.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->orderBy('v.id', 'ASC')
    //            ->setMaxResults(10)
    //            ->getQuery()
    //            ->getResult()
    //        ;
    //    }

    //    public function findOneBySomeField($value): ?Vol
    //    {
    //        return $this->createQueryBuilder('v')
    //            ->andWhere('v.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->getQuery()
    //            ->getOneOrNullResult()
    //        ;
    //    }
}
