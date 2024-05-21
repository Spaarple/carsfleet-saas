<?php

namespace App\Repository;

use App\Entity\HeadOffice;
use App\Entity\User\UserSuperAdministrator;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\QueryBuilder;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Bridge\Doctrine\Types\UuidType;

/**
 * @extends ServiceEntityRepository<HeadOffice>
 *
 * @method HeadOffice|null find($id, $lockMode = null, $lockVersion = null)
 * @method HeadOffice|null findOneBy(array $criteria, array $orderBy = null)
 * @method HeadOffice[]    findAll()
 * @method HeadOffice[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class HeadOfficeRepository extends ServiceEntityRepository
{
    /**
     * @param ManagerRegistry $registry
     */
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, HeadOffice::class);
    }

    /**
     * @param UserSuperAdministrator $userSuperAdministrator
     * @return QueryBuilder
     */
    public function getHeadOfficeByUser(UserSuperAdministrator $userSuperAdministrator): QueryBuilder
    {
        return $this->createQueryBuilder('h')
            ->where('h.id = :headOfficeId')
            ->setParameter(
                'headOfficeId',
                $userSuperAdministrator->getHeadOffice()?->getId(),
                UuidType::NAME
            );
    }
}
