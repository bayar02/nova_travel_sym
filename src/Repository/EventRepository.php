<?php

namespace App\Repository;

use App\Entity\Event;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Event>
 */
class EventRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Event::class);
    }

    /**
<<<<<<< HEAD
     * Find upcoming events
     * 
     * @param int $limit The maximum number of results to retrieve
     * @return Event[] Returns an array of upcoming Event objects
     */
    public function findUpcoming(int $limit = 10): array
    {
        return $this->createQueryBuilder('e')
            ->orderBy('e.date_event', 'ASC')
            ->setMaxResults($limit)
=======
     * Find all Event entities sorted by name.
     *
     * @return Event[] Returns an array of Event objects
     */
    public function findAll(): array
    {
        return $this->createQueryBuilder('e')
            ->orderBy('e.nom', 'ASC') // Sorting events alphabetically by name
>>>>>>> f5842df (Initial commit for Events branch)
            ->getQuery()
            ->getResult();
    }

<<<<<<< HEAD
    //    /**
    //     * @return Event[] Returns an array of Event objects
    //     */
    //    public function findByExampleField($value): array
    //    {
    //        return $this->createQueryBuilder('e')
    //            ->andWhere('e.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->orderBy('e.id', 'ASC')
    //            ->setMaxResults(10)
    //            ->getQuery()
    //            ->getResult()
    //        ;
    //    }

    //    public function findOneBySomeField($value): ?Event
    //    {
    //        return $this->createQueryBuilder('e')
    //            ->andWhere('e.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->getQuery()
    //            ->getOneOrNullResult()
    //        ;
    //    }
=======
    /**
     * Search events by name (partial match).
     *
     * @param string $name
     * @return Event[] Returns an array of matching Event objects
     */
    public function searchByName(string $name): array
    {
        return $this->createQueryBuilder('e')
            ->andWhere('e.nom LIKE :name')
            ->setParameter('name', '%' . $name . '%') // Allow partial matches
            ->orderBy('e.nom', 'ASC') // Keep the result sorted
            ->getQuery()
            ->getResult();
    }

    /**
     * Get all events sorted by name.
     *
     * @param string $order ASC or DESC
     * @return Event[] Returns an array of sorted Event objects
     */
    public function getSortedByName(string $order = 'ASC'): array
    {
        return $this->createQueryBuilder('e')
            ->orderBy('e.nom', $order)
            ->getQuery()
            ->getResult();
    }
>>>>>>> f5842df (Initial commit for Events branch)
}
