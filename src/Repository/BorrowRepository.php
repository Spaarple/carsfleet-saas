<?php

namespace App\Repository;

use App\Entity\Borrow;
use App\Entity\User\UserAdministratorSite;
use App\Entity\User\UserEmployed;
use App\Entity\User\UserAdministratorHeadOffice;
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
     * @param UserAdministratorHeadOffice|UserAdministratorSite $user
     * @return QueryBuilder
     */
    public function getBorrowByUser(UserAdministratorHeadOffice|UserAdministratorSite $user): QueryBuilder
    {
        $queryBuilder = $this->createQueryBuilder('b')
            ->innerJoin('b.car', 'c')
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
     * @param UserAdministratorHeadOffice|UserAdministratorSite $user
     * @return array<Borrow>
     */
    public function getBorrowUserByDate(UserAdministratorHeadOffice|UserAdministratorSite $user): array
    {
        $borrowings = $this->getBorrowByUser($user)->getQuery()->getResult();

        $borrowByDate = [];
        foreach ($borrowings as $borrowing) {
            $borrowDate = $borrowing->getStartDate()->format('Y');
            if (!isset($borrowByDate[$borrowDate])) {
                $borrowByDate[$borrowDate] = 0;
            }
            $borrowByDate[$borrowDate]++;
        }

        return $borrowByDate;
    }

    /**
     * @return array
     */
    public function getAllBorrowByDate(): array
    {
        $borrowings = $this->findAll();

        $borrowByDate = [];
        foreach ($borrowings as $borrowing) {
            $borrowDate = $borrowing->getStartDate()?->format('Y');
            if (!isset($borrowByDate[$borrowDate])) {
                $borrowByDate[$borrowDate] = 0;
            }
            $borrowByDate[$borrowDate]++;
        }

        return $borrowByDate;
    }

    /**
     * @param UserEmployed $user
     * @return array<Borrow>
     */
    public function findCurrentBorrowsByUserSite(UserEmployed $user): array
    {
        return $this->createQueryBuilder('b')
            ->join('b.car', 'c')
            ->leftJoin('b.userEmployed', 'u')
            ->where('c.site = :site')
            ->andWhere('c.status = :status')
            ->andWhere('b.startDate >= CURRENT_DATE()')
            ->andWhere('c.passengerQuantity > SIZE(b.userEmployed)')
            ->andWhere('u.id != :userId OR u.id IS NULL')
            ->setParameter('status', StatusCars::AVAILABLE)
            ->setParameter('site', $user->getSite()?->getId(), UuidType::NAME)
            ->setParameter('userId', $user->getId(), UuidType::NAME)
            ->getQuery()->getResult();
    }
}
