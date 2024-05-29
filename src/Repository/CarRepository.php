<?php

namespace App\Repository;

use App\Entity\Car;
use App\Entity\User\UserAdministratorSite;
use App\Entity\User\UserAdministratorHeadOffice;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
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
}
