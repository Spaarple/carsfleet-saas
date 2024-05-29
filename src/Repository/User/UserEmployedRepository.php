<?php

declare(strict_types=1);

namespace App\Repository\User;

use App\Entity\User\UserAdministratorSite;
use App\Entity\User\UserEmployed;
use App\Entity\User\UserAdministratorHeadOffice;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\QueryBuilder;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Bridge\Doctrine\Types\UuidType;

/**
 * @extends ServiceEntityRepository<UserEmployed>
 *
 * @method UserEmployed|null find($id, $lockMode = null, $lockVersion = null)
 * @method UserEmployed|null findOneBy(array $criteria, array $orderBy = null)
 * @method UserEmployed[]    findAll()
 * @method UserEmployed[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class UserEmployedRepository extends ServiceEntityRepository
{
    /**
     * @param ManagerRegistry $registry
     */
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, UserEmployed::class);
    }

    /**
     * @param UserAdministratorHeadOffice|UserAdministratorSite $user
     * @return QueryBuilder
     */
    public function getEmployees(UserAdministratorHeadOffice|UserAdministratorSite $user): QueryBuilder
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
