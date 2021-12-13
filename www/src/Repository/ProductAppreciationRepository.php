<?php

namespace App\Repository;

use App\Entity\ProductAppreciation;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method ProductAppreciation|null find($id, $lockMode = null, $lockVersion = null)
 * @method ProductAppreciation|null findOneBy(array $criteria, array $orderBy = null)
 * @method ProductAppreciation[]    findAll()
 * @method ProductAppreciation[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ProductAppreciationRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, ProductAppreciation::class);
    }

    // /**
    //  * @return ProductAppreciation[] Returns an array of ProductAppreciation objects
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
    public function findOneBySomeField($value): ?ProductAppreciation
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
