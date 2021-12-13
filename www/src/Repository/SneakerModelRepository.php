<?php

namespace App\Repository;

use App\Entity\SneakerModel;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method SneakerModel|null find($id, $lockMode = null, $lockVersion = null)
 * @method SneakerModel|null findOneBy(array $criteria, array $orderBy = null)
 * @method SneakerModel[]    findAll()
 * @method SneakerModel[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class SneakerModelRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, SneakerModel::class);
    }

    // /**
    //  * @return SneakerModel[] Returns an array of SneakerModel objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('s')
            ->andWhere('s.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('s.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?SneakerModel
    {
        return $this->createQueryBuilder('s')
            ->andWhere('s.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
