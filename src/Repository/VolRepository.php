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
     * @param array $criteria Search criteria (destination, dateStart, dateEnd, aeroport_arrivee)
     * @param string $sortBy Field to sort by (date, price_asc, price_desc)
     * @return \Doctrine\ORM\QueryBuilder Returns a query builder for pagination
     */
    public function searchFlights(array $criteria, string $sortBy = 'date'): \Doctrine\ORM\QueryBuilder
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

        // Filter by arrival airport if specified
        if (!empty($criteria['aeroport_arrivee'])) {
            $queryBuilder
                ->andWhere('LOWER(v.aeroport_arrivee) LIKE LOWER(:aeroport_arrivee)')
                ->setParameter('aeroport_arrivee', '%' . $criteria['aeroport_arrivee'] . '%');
        }
        
        // Filter by date range if specified
        if (!empty($criteria['dateStart']) || !empty($criteria['dateEnd'])) {
            if (!empty($criteria['dateStart'])) {
                $startDate = new \DateTime($criteria['dateStart']);
                $queryBuilder
                    ->andWhere('v.date_depart >= :startDate')
                    ->setParameter('startDate', $startDate);
            }
            
            if (!empty($criteria['dateEnd'])) {
                $endDate = new \DateTime($criteria['dateEnd']);
                $queryBuilder
                    ->andWhere('v.date_depart <= :endDate')
                    ->setParameter('endDate', $endDate);
            }
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
        
        return $queryBuilder;
    }

    /**
     * Search airports for autocomplete
     * 
     * @param string $term Search term
     * @param string $type Type of search ('destination' or 'arrival')
     * @return array Returns matching airports
     */
    public function searchAirports(string $term, string $type = 'arrival'): array
    {
        $qb = $this->createQueryBuilder('v');
        
        if ($type === 'destination') {
            $qb->select('DISTINCT v.destination')
               ->where('LOWER(v.destination) LIKE LOWER(:term)')
               ->setParameter('term', '%' . $term . '%');
        } else {
            $qb->select('DISTINCT v.aeroport_arrivee')
               ->where('LOWER(v.aeroport_arrivee) LIKE LOWER(:term)')
               ->setParameter('term', '%' . $term . '%');
        }
        
        return $qb->getQuery()->getResult();
    }

    /**
     * Find flights between two dates for calendar display
     * 
     * @param \DateTime $start Start date
     * @param \DateTime $end End date
     * @return Vol[] Returns an array of Vol objects
     */
    public function findFlightsBetweenDates(\DateTime $start, \DateTime $end): array
    {
        return $this->createQueryBuilder('v')
            ->andWhere('v.date_depart >= :start')
            ->andWhere('v.date_depart <= :end')
            ->setParameter('start', $start)
            ->setParameter('end', $end)
            ->orderBy('v.date_depart', 'ASC')
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
