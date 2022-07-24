<?php

namespace App\Repository;

use App\Entity\Sneaker;
use App\Entity\User;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Sneaker|null find($id, $lockMode = null, $lockVersion = null)
 * @method Sneaker|null findOneBy(array $criteria, array $orderBy = null)
 * @method Sneaker[]    findAll()
 * @method Sneaker[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class SneakerRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Sneaker::class);
    }

    // /**
    //  * @return Sneaker[] Returns an array of Sneaker objects
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
    public function findOneBySomeField($value): ?Sneaker
    {
        return $this->createQueryBuilder('s')
            ->andWhere('s.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */

    public function findUserSneakersByInvoiceStatus(string $status, User $user): ?Array
    {
        $entityManager = $this->getEntityManager();
        return $entityManager->createQuery('SELECT sneaker FROM App\Entity\Invoice invoice, App\Entity\Sneaker sneaker WHERE invoice.sneaker = sneaker.id AND sneaker.publisher = :user AND invoice.paymentStatus = :status')
            ->setParameter('user', $user->getId())
            ->setParameter('status', $status)
            ->getResult(\Doctrine\ORM\Query::HYDRATE_OBJECT)
            ;
    }

    public function findSneakersByInvoiceStatus(string $status): ?Array
    {
        $entityManager = $this->getEntityManager();
        return $entityManager->createQuery('SELECT sneaker FROM App\Entity\Invoice invoice, App\Entity\Sneaker sneaker WHERE invoice.sneaker = sneaker.id AND invoice.paymentStatus = :status')
            ->setParameter('status', $status)
            ->getResult(\Doctrine\ORM\Query::HYDRATE_OBJECT)
            ;
    }


    public function findSearch(): array
    {
        return $this -> findAll();
    }
}
