<?php

namespace App\Repository;

use App\Entity\ReportReason;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method ReportReason|null find($id, $lockMode = null, $lockVersion = null)
 * @method ReportReason|null findOneBy(array $criteria, array $orderBy = null)
 * @method ReportReason[]    findAll()
 * @method ReportReason[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ReportReasonRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, ReportReason::class);
    }

    // /**
    //  * @return ReportReason[] Returns an array of ReportReason objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('r')
            ->andWhere('r.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('r.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?ReportReason
    {
        return $this->createQueryBuilder('r')
            ->andWhere('r.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
