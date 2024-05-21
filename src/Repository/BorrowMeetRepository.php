<?php

namespace App\Repository;

use App\Entity\BorrowMeet;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<BorrowMeet>
 *
 * @method BorrowMeet|null find($id, $lockMode = null, $lockVersion = null)
 * @method BorrowMeet|null findOneBy(array $criteria, array $orderBy = null)
 * @method BorrowMeet[]    findAll()
 * @method BorrowMeet[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class BorrowMeetRepository extends ServiceEntityRepository
{
    /**
     * @param ManagerRegistry $registry
     */
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, BorrowMeet::class);
    }
}
