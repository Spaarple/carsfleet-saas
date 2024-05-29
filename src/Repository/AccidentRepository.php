<?php

namespace App\Repository;

use App\Entity\Accident;
use App\Entity\User\UserAdministrator;
use App\Entity\User\UserSuperAdministrator;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\QueryBuilder;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Bridge\Doctrine\Types\UuidType;

/**
 * @extends ServiceEntityRepository<Accident>
 *
 * @method Accident|null find($id, $lockMode = null, $lockVersion = null)
 * @method Accident|null findOneBy(array $criteria, array $orderBy = null)
 * @method Accident[]    findAll()
 * @method Accident[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class AccidentRepository extends ServiceEntityRepository
{
    /**
     * @param ManagerRegistry $registry
     */
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Accident::class);
    }

    /**
     * @param UserSuperAdministrator|UserAdministrator $user
     * @return QueryBuilder
     */
    public function getAccidentByUser(UserSuperAdministrator|UserAdministrator $user): QueryBuilder
    {
        $queryBuilder = $this->createQueryBuilder('a')
            ->innerJoin('a.car', 'c')
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
     * @param UserSuperAdministrator|UserAdministrator $user
     * @return array<Accident>
     */
    public function getAccidentUserByDate(UserSuperAdministrator|UserAdministrator $user): array
    {
        $borrowings = $this->getAccidentByUser($user)->getQuery()->getResult();

        $accidentByDate = [];
        foreach ($borrowings as $borrowing) {
            $accidentDate = $borrowing->getDate()->format('Y');
            if (!isset($accidentByDate[$accidentDate])) {
                $accidentByDate[$accidentDate] = 0;
            }
            $accidentByDate[$accidentDate]++;
        }

        return $accidentByDate;
    }
}
