<?php

namespace App\Repository;

use App\Entity\Key;
use App\Entity\User\UserAdministrator;
use App\Entity\User\UserSuperAdministrator;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\QueryBuilder;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Bridge\Doctrine\Types\UuidType;

/**
 * @extends ServiceEntityRepository<Key>
 *
 * @method Key|null find($id, $lockMode = null, $lockVersion = null)
 * @method Key|null findOneBy(array $criteria, array $orderBy = null)
 * @method Key[]    findAll()
 * @method Key[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class KeyRepository extends ServiceEntityRepository
{
    /**
     * @param ManagerRegistry $registry
     */
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Key::class);
    }

    /**
     * @param UserSuperAdministrator|UserAdministrator $user
     * @return QueryBuilder
     */
    public function getKeysByUser(UserSuperAdministrator|UserAdministrator $user): QueryBuilder
    {
        $queryBuilder = $this->createQueryBuilder('k')
            ->innerJoin('k.car', 'c')
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
}
