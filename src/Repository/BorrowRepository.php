<?php

namespace App\Repository;

use App\Entity\Borrow;
use App\Entity\User\UserAdministrator;
use App\Entity\User\UserEmployed;
use App\Entity\User\UserSuperAdministrator;
use App\Enum\StatusCars;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\QueryBuilder;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Bridge\Doctrine\Types\UuidType;

/**
 * @extends ServiceEntityRepository<Borrow>
 *
 * @method Borrow|null find($id, $lockMode = null, $lockVersion = null)
 * @method Borrow|null findOneBy(array $criteria, array $orderBy = null)
 * @method Borrow[]    findAll()
 * @method Borrow[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class BorrowRepository extends ServiceEntityRepository
{
    /**
     * @param ManagerRegistry $registry
     */
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Borrow::class);
    }

    /**
     * @param UserSuperAdministrator|UserAdministrator $user
     * @return QueryBuilder
     */
    public function getBorrowByUser(UserSuperAdministrator|UserAdministrator $user): QueryBuilder
    {
        $queryBuilder = $this->createQueryBuilder('b')
            ->innerJoin('b.car', 'c')
            ->innerJoin('c.site', 's');

        if ($user instanceof UserSuperAdministrator) {
            $queryBuilder
                ->innerJoin('s.headOffice', 'h')
                ->where('h.id = :headOfficeId')
                ->setParameter(
                    'headOfficeId',
                    $user->getHeadOffice()?->getId(),
                    UuidType::NAME
                );
        }
        if ($user instanceof UserAdministrator) {
            $queryBuilder
                ->where('s.id = :siteId')
                ->setParameter(
                    'siteId',
                    $user->getSite()?->getId(),
                    UuidType::NAME
                );
        }

        return $queryBuilder;
    }

    /**
     * @param UserEmployed $user
     * @return array
     */
    public function findCurrentBorrowsByUserSite(UserEmployed $user): array
    {
        $qb = $this->createQueryBuilder('b');

        return
            $qb->join('b.car', 'c')
                ->where('c.site = :site')
                ->andWhere('c.status = :status')
                ->andWhere('b.startDate >= CURRENT_DATE()')
                ->andWhere('c.passengerQuantity > SIZE(b.userEmployed)')
                ->setParameter('status', StatusCars::AVAILABLE)
                ->setParameter('site', $user->getSite()?->getId(), UuidType::NAME)
                ->getQuery()->getResult();
    }
}
