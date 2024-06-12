<?php

declare(strict_types=1);

namespace App\Repository\User;

use App\Entity\User\UserAdministratorHeadOffice;
use App\Entity\User\UserAdministratorSite;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\QueryBuilder;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Bridge\Doctrine\Types\UuidType;

/**
 * @extends ServiceEntityRepository<UserAdministratorSite>
 *
 * @method UserAdministratorSite|null find($id, $lockMode = null, $lockVersion = null)
 * @method UserAdministratorSite|null findOneBy(array $criteria, array $orderBy = null)
 * @method UserAdministratorSite[]    findAll()
 * @method UserAdministratorSite[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class UserAdministratorSiteRepository extends ServiceEntityRepository
{
    /**
     * @param ManagerRegistry $registry
     */
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, UserAdministratorSite::class);
    }

    /**
     * @param UserAdministratorHeadOffice|UserAdministratorSite $user
     * @return QueryBuilder
     */
    public function getAdmin(UserAdministratorHeadOffice|UserAdministratorSite $user): QueryBuilder
    {
        $queryBuilder = $this->createQueryBuilder('u')
            ->innerJoin('u.site', 's');

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
