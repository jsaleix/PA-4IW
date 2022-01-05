<?php

namespace App\Repository;

use App\Entity\PriceProposal;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method PriceProposal|null find($id, $lockMode = null, $lockVersion = null)
 * @method PriceProposal|null findOneBy(array $criteria, array $orderBy = null)
 * @method PriceProposal[]    findAll()
 * @method PriceProposal[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class PriceProposalRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, PriceProposal::class);
    }

    // /**
    //  * @return PriceProposal[] Returns an array of PriceProposal objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('p')
            ->andWhere('p.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('p.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?PriceProposal
    {
        return $this->createQueryBuilder('p')
            ->andWhere('p.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
