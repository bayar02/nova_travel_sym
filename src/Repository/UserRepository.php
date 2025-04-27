<?php

namespace App\Repository;

use App\Entity\User;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<User>
 */
class UserRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, User::class);
    }

    //    /**
    //     * @return User[] Returns an array of User objects
    //     */
    //    public function findByExampleField($value): array
    //    {
    //        return $this->createQueryBuilder('u')
    //            ->andWhere('u.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->orderBy('u.id', 'ASC')
    //            ->setMaxResults(10)
    //            ->getQuery()
    //            ->getResult()
    //        ;
    //    }

    //    public function findOneBySomeField($value): ?User
    //    {
    //        return $this->createQueryBuilder('u')
    //            ->andWhere('u.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->getQuery()
    //            ->getOneOrNullResult()
    //        ;
    //    }

    /**
     * Search users by various fields
     * @param string|null $searchTerm Search term to look for in nom, prenom, mail, and cin
     * @return User[]
     */
    public function searchUsers(?string $searchTerm): array
    {
        if (!$searchTerm) {
            return $this->findAll();
        }

        return $this->createQueryBuilder('u')
            ->where('u.nom LIKE :searchTerm')
            ->orWhere('u.prenom LIKE :searchTerm')
            ->orWhere('u.mail LIKE :searchTerm')
            ->orWhere('u.cin LIKE :searchTerm')
            ->setParameter('searchTerm', '%' . $searchTerm . '%')
            ->getQuery()
            ->getResult();
    }

    /**
     * Sort users by a specific field
     * @param string $field Field to sort by (id, nom, prenom, mail, cin)
     * @param string $direction Sort direction (ASC or DESC)
     * @return User[]
     */
    public function sortUsers(string $field = 'id', string $direction = 'ASC'): array
    {
        $validFields = ['id', 'nom', 'prenom', 'mail', 'cin'];
        $validDirections = ['ASC', 'DESC'];

        if (!in_array($field, $validFields)) {
            $field = 'id';
        }
        if (!in_array($direction, $validDirections)) {
            $direction = 'ASC';
        }

        return $this->createQueryBuilder('u')
            ->orderBy('u.' . $field, $direction)
            ->getQuery()
            ->getResult();
    }

    /**
     * Search and sort users
     * @param string|null $searchTerm Search term
     * @param string $sortField Field to sort by
     * @param string $sortDirection Sort direction
     * @return User[]
     */
    public function searchAndSortUsers(?string $searchTerm, string $sortField = 'id', string $sortDirection = 'ASC'): array
    {
        $qb = $this->createQueryBuilder('u');

        if ($searchTerm) {
            $qb->where('u.nom LIKE :searchTerm')
               ->orWhere('u.prenom LIKE :searchTerm')
               ->orWhere('u.mail LIKE :searchTerm')
               ->orWhere('u.cin LIKE :searchTerm')
               ->setParameter('searchTerm', '%' . $searchTerm . '%');
        }

        $validFields = ['id', 'nom', 'prenom', 'mail', 'cin'];
        $validDirections = ['ASC', 'DESC'];

        if (!in_array($sortField, $validFields)) {
            $sortField = 'id';
        }
        if (!in_array($sortDirection, $validDirections)) {
            $sortDirection = 'ASC';
        }

        return $qb->orderBy('u.' . $sortField, $sortDirection)
                 ->getQuery()
                 ->getResult();
    }
}
