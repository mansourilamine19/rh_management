<?php

namespace App\Repository;

use App\Entity\User;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\Security\Core\Exception\UnsupportedUserException;
use Symfony\Component\Security\Core\User\PasswordAuthenticatedUserInterface;
use Symfony\Component\Security\Core\User\PasswordUpgraderInterface;
use Doctrine\ORM\Tools\Pagination\Paginator;

/**
 * @extends ServiceEntityRepository<User>
 */
class UserRepository extends ServiceEntityRepository implements PasswordUpgraderInterface {

    protected $defaultLimit = 10;
    protected $maxLimit = 100;

    public function __construct(ManagerRegistry $registry) {
        parent::__construct($registry, User::class);
    }

    /**
     * Used to upgrade (rehash) the user's password automatically over time.
     */
    public function upgradePassword(PasswordAuthenticatedUserInterface $user, string $newHashedPassword): void {
        if (!$user instanceof User) {
            throw new UnsupportedUserException(sprintf('Instances of "%s" are not supported.', $user::class));
        }

        $user->setPassword($newHashedPassword);
        $this->getEntityManager()->persist($user);
        $this->getEntityManager()->flush();
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

    public function searchPaginate(int $page, $search) {

        $limit = $limit ?? $this->defaultLimit;
        $limit = min($limit, $this->maxLimit);
        $offset = ($page - 1) * $limit;
        // Set pagination in query
        $queryBuilder = $this->createQueryBuilder('u')
                ->setMaxResults($limit)
                ->setFirstResult($offset);
        if (isset($search["role"]) && !(empty($search['role']))) {
            $queryBuilder->andWhere('u.roles LIKE :role')
                    ->setParameter('role', "%{$search["role"]}%");
        }
        if (isset($search["search"]) && !(empty($search['search']))) {
            $queryBuilder->andWhere('u.fullName LIKE :search OR u.email LIKE :search OR u.tel LIKE :search')
                    ->setParameter('search', "%{$search["search"]}%");
        }
        $queryBuilder->orderBy('u.id', 'desc');
        // Get total results and paginated results
        $paginator = new Paginator($queryBuilder);
        return $paginator;
    }
}
