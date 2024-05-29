<?php

declare(strict_types=1);

namespace App\Repository\User;

use App\Entity\User\UserAdministratorHeadOffice;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\QueryBuilder;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Bridge\Doctrine\Types\UuidType;

/**
 * @extends ServiceEntityRepository<UserAdministratorHeadOffice>
 *
 * @method UserAdministratorHeadOffice|null find($id, $lockMode = null, $lockVersion = null)
 * @method UserAdministratorHeadOffice|null findOneBy(array $criteria, array $orderBy = null)
 * @method UserAdministratorHeadOffice[]    findAll()
 * @method UserAdministratorHeadOffice[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class UserAdministratorHeadOfficeRepository extends ServiceEntityRepository
{
    /**
     * @param ManagerRegistry $registry
     */
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, UserAdministratorHeadOffice::class);
    }

    /**
     * @param UserAdministratorHeadOffice $user
     * @return QueryBuilder
     */
    public function getSuperAdmin(UserAdministratorHeadOffice $user): QueryBuilder
    {
        return $this->createQueryBuilder('u')
            ->innerJoin('u.headOffice', 'h')
            ->where('h.id = :headOfficeId')
            ->setParameter('headOfficeId', $user->getHeadOffice()?->getId(), UuidType::NAME);
    }
}
