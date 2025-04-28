<?php

namespace App\Repository;

use App\Entity\User;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;
use Doctrine\ORM\QueryBuilder;

/**
 * @extends ServiceEntityRepository<User>
 */
class UserRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, User::class);
    }

    /**
     * Search users by various criteria
     */
    public function searchUsers(string $query = null, array $roles = [], bool $verified = null): array
    {
        $qb = $this->createQueryBuilder('u');

        if ($query) {
            $qb->andWhere(
                $qb->expr()->orX(
                    $qb->expr()->like('u.nom', ':query'),
                    $qb->expr()->like('u.prenom', ':query'),
                    $qb->expr()->like('u.mail', ':query'),
                    $qb->expr()->like('u.cin', ':query')
                )
            )
            ->setParameter('query', '%' . $query . '%');
        }

        if (!empty($roles)) {
            $qb->andWhere('u.roles LIKE :roles')
               ->setParameter('roles', '%' . implode('%', $roles) . '%');
        }

        if ($verified !== null) {
            $qb->andWhere('u.isVerified = :verified')
               ->setParameter('verified', $verified);
        }

        return $qb->orderBy('u.nom', 'ASC')
                  ->getQuery()
                  ->getResult();
    }

    /**
     * Get users in a tree structure based on roles
     */
    public function getUsersTree(): array
    {
        $users = $this->findAll();
        $tree = [
            'ROLE_ADMIN' => [],
            'ROLE_USER' => []
        ];

        foreach ($users as $user) {
            $roles = $user->getRoles();
            if (in_array('ROLE_ADMIN', $roles)) {
                $tree['ROLE_ADMIN'][] = $user;
            } else {
                $tree['ROLE_USER'][] = $user;
            }
        }

        return $tree;
    }

    /**
     * Get users with pagination and search/filtering
     */
    public function getPaginatedUsers(int $page = 1, int $limit = 10, ?string $searchQuery = null, ?string $roleFilter = null, ?string $verifiedFilter = null): array
    {
        $qb = $this->createQueryBuilder('u');

        // Apply search
        if ($searchQuery) {
            $qb->andWhere(
                $qb->expr()->orX(
                    $qb->expr()->like('u.nom', ':query'),
                    $qb->expr()->like('u.prenom', ':query'),
                    $qb->expr()->like('u.mail', ':query'),
                    $qb->expr()->like('u.cin', ':query')
                )
            )
            ->setParameter('query', '%' . $searchQuery . '%');
        }

        // Apply role filter
        if ($roleFilter === 'ROLE_ADMIN') {
            $qb->andWhere('u.roles LIKE :role')
               ->setParameter('role', '%ROLE_ADMIN%');
        } elseif ($roleFilter === 'ROLE_USER') {
            $qb->andWhere('u.roles NOT LIKE :role')
               ->setParameter('role', '%ROLE_ADMIN%');
        }
        // If $roleFilter is empty or null, do not apply any role filter

        // Apply verification filter
        if ($verifiedFilter !== null && $verifiedFilter !== '') {
            $qb->andWhere('u.isVerified = :verified')
               ->setParameter('verified', (bool)$verifiedFilter);
        }

        // Get total count before pagination
        $countQb = clone $qb;
        $countQb->select('COUNT(u.id)');
        $total = $countQb->getQuery()->getSingleScalarResult();

        // Apply pagination
        $qb->setFirstResult(($page - 1) * $limit)
           ->setMaxResults($limit)
           ->orderBy('u.nom', 'ASC');

        return [
            'users' => $qb->getQuery()->getResult(),
            'total' => $total,
            'pages' => ceil($total / $limit)
        ];
    }

    /**
     * Find users by role
     */
    public function findByRole(string $role): array
    {
        return $this->createQueryBuilder('u')
            ->andWhere('u.roles LIKE :role')
            ->setParameter('role', '%' . $role . '%')
            ->getQuery()
            ->getResult();
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
