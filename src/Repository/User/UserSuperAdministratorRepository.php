<?php

declare(strict_types=1);

namespace App\Repository\User;

use App\Entity\User\UserSuperAdministrator;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\QueryBuilder;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Bridge\Doctrine\Types\UuidType;

/**
 * @extends ServiceEntityRepository<UserSuperAdministrator>
 *
 * @method UserSuperAdministrator|null find($id, $lockMode = null, $lockVersion = null)
 * @method UserSuperAdministrator|null findOneBy(array $criteria, array $orderBy = null)
 * @method UserSuperAdministrator[]    findAll()
 * @method UserSuperAdministrator[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class UserSuperAdministratorRepository extends ServiceEntityRepository
{
    /**
     * @param ManagerRegistry $registry
     */
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, UserSuperAdministrator::class);
    }

    /**
     * @param UserSuperAdministrator $user
     * @return QueryBuilder
     */
    public function getSuperAdmin(UserSuperAdministrator $user): QueryBuilder
    {
        $queryBuilder = $this->createQueryBuilder('u')
            ->innerJoin('u.headOffice', 'h');

        $queryBuilder
            ->where('h.id = :headOfficeId')
            ->setParameter(
                'headOfficeId',
                $user->getHeadOffice()?->getId(),
                UuidType::NAME
            );

        return $queryBuilder;
    }
}
