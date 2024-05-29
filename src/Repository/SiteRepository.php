<?php

namespace App\Repository;

use App\Entity\Site;
use App\Entity\User\UserAdministratorHeadOffice;
use App\Entity\User\UserAdministratorSite;
use App\Entity\User\UserSuperAdministrator;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\QueryBuilder;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Bridge\Doctrine\Types\UuidType;

/**
 * @extends ServiceEntityRepository<Site>
 *
 * @method Site|null find($id, $lockMode = null, $lockVersion = null)
 * @method Site|null findOneBy(array $criteria, array $orderBy = null)
 * @method Site[]    findAll()
 * @method Site[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class SiteRepository extends ServiceEntityRepository
{
    /**
     * @param ManagerRegistry $registry
     */
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Site::class);
    }

    /**
     * @param UserAdministratorHeadOffice|UserAdministratorSite $user
     * @return QueryBuilder
     */
    public function getSitesByHeadOffice(UserAdministratorHeadOffice|UserAdministratorSite $user): QueryBuilder
    {
        $queryBuilder = $this->createQueryBuilder('s')
            ->innerJoin('s.headOffice', 'h');

        if ($user instanceof UserAdministratorHeadOffice) {
            $queryBuilder
                ->where('h.id = :headOfficeId')
                ->setParameter(
                    'headOfficeId',
                    $user->getHeadOffice()?->getId(),
                    UuidType::NAME
                );
        }

        if ($user instanceof UserAdministratorSite) {
            $queryBuilder
                ->innerJoin('s.userAdministratorSite', 'a')
                ->where('a.id = :administratorId')
                ->setParameter(
                    'administratorId',
                    $user->getId(),
                    UuidType::NAME
                );
        }

        return $queryBuilder;
    }
}
