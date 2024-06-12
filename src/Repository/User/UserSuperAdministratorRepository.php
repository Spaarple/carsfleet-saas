<?php

declare(strict_types=1);

namespace App\Repository\User;

use App\Entity\User\UserSuperAdministrator;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

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
}
