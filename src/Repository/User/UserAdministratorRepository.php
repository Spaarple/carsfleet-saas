<?php

declare(strict_types=1);

namespace App\Repository\User;

use App\Entity\User\UserAdministrator;
use App\Entity\User\UserSuperAdministrator;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\QueryBuilder;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Bridge\Doctrine\Types\UuidType;

/**
 * @extends ServiceEntityRepository<UserAdministrator>
 *
 * @method UserAdministrator|null find($id, $lockMode = null, $lockVersion = null)
 * @method UserAdministrator|null findOneBy(array $criteria, array $orderBy = null)
 * @method UserAdministrator[]    findAll()
 * @method UserAdministrator[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class UserAdministratorRepository extends ServiceEntityRepository
{
    /**
     * @param ManagerRegistry $registry
     */
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, UserAdministrator::class);
    }

    /**
     * @param UserSuperAdministrator|UserAdministrator $user
     * @return QueryBuilder
     */
    public function getAdmin(UserSuperAdministrator|UserAdministrator $user): QueryBuilder
    {
        $queryBuilder = $this->createQueryBuilder('u')
            ->innerJoin('u.site', 's');

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
}
