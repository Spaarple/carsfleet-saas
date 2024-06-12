<?php

namespace App\Repository;

use App\DTO\SearchCarDTO;
use App\Entity\Car;
use App\Entity\User\UserAdministratorSite;
use App\Entity\User\UserAdministratorHeadOffice;
use App\Enum\StatusCars;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\Query\Expr\Join;
use Doctrine\ORM\QueryBuilder;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Bridge\Doctrine\Types\UuidType;

/**
 * @extends ServiceEntityRepository<Car>
 *
 * @method Car|null find($id, $lockMode = null, $lockVersion = null)
 * @method Car|null findOneBy(array $criteria, array $orderBy = null)
 * @method Car[]    findAll()
 * @method Car[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class CarRepository extends ServiceEntityRepository
{
    /**
     * @param ManagerRegistry $registry
     */
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Car::class);
    }

    /**
     * @param UserAdministratorHeadOffice|UserAdministratorSite $user
     * @return QueryBuilder
     */
    public function getCarsByUser(UserAdministratorHeadOffice|UserAdministratorSite $user): QueryBuilder
    {
        $queryBuilder = $this->createQueryBuilder('c')
            ->innerJoin('c.site', 's');

        if ($user instanceof UserAdministratorHeadOffice) {
            $queryBuilder
                ->innerJoin('s.headOffice', 'h')
                ->where('h.id = :headOfficeId')
                ->setParameter(
                    'headOfficeId',
                    $user->getHeadOffice()?->getId(),
                    UuidType::NAME
                );
        }

        if ($user instanceof UserAdministratorSite) {
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
     * @param SearchCarDTO $search
     * @param $site
     *
     * @return array
     */
    public function findSearchCar(SearchCarDTO $search, $site): array
    {
        $query = $this->createQueryBuilder('c')
            ->andWhere('c.status = :status')
            ->setParameter('status', StatusCars::AVAILABLE)
            ->andWhere('c.site = :site')
            ->setParameter('site', $site->getId(), UuidType::NAME);

        if ($search->from) {
            $query = $query
                ->leftJoin(
                    'c.borrows', 'b',
                    Join::WITH,
                    'b.startDate <= :endDate AND b.endDate >= :startDate'
                )
                ->andWhere('b.id IS NULL')
                ->setParameter('startDate', $search->from)
                ->setParameter('endDate', $search->to ?? new \DateTime('+3 month'));
        }

        if ($search->gearbox) {
            $query = $query
                ->andWhere('c.gearbox = :gearbox')
                ->setParameter('gearbox', $search->gearbox);
        }

        return $query->getQuery()->getResult();
    }
}
