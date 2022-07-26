<?php

namespace App\Repository;

use App\Entity\Invoice;
use App\Entity\User;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;
use phpDocumentor\Reflection\Types\Boolean;

/**
 * @method Invoice|null find($id, $lockMode = null, $lockVersion = null)
 * @method Invoice|null findOneBy(array $criteria, array $orderBy = null)
 * @method Invoice[]    findAll()
 * @method Invoice[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class InvoiceRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Invoice::class);
    }

    // /**
    //  * @return Invoice[] Returns an array of Invoice objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('i')
            ->andWhere('i.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('i.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */


    /*public function findUserInvoicesByStatus(string $status, User $user): ?Array
    {
        return $this->createQueryBuilder('SELECT * from invoice, sneaker WHERE  invoice.sneaker = sneaker.id AND sneaker.publisher =' . $user->getId())
            ->andWhere('invoice.paymentStatus = :status')
            ->setParameter('status', $status)
            ->getQuery()
            ->getResult()
        ;
    }*/

    public function findUserInvoicesByStatus(string $status, User $user): ?Array
    {
        $entityManager = $this->getEntityManager();
        return $entityManager->createQuery('SELECT invoice FROM App\Entity\Invoice invoice, App\Entity\Sneaker sneaker WHERE invoice.sneaker = sneaker.id AND sneaker.publisher = :user AND invoice.paymentStatus = :status')
               ->setParameter('user', $user->getId())
               ->setParameter('status', $status)
               ->getResult(\Doctrine\ORM\Query::HYDRATE_OBJECT)
               ;
    }

    public function findShopInvoicesByStatus(string $status): ?Array
    {
        $entityManager = $this->getEntityManager();
        return $entityManager->createQuery('SELECT invoice FROM App\Entity\Invoice invoice, App\Entity\Sneaker sneaker WHERE invoice.sneaker = sneaker.id AND sneaker.from_shop = \'1\' AND invoice.paymentStatus = :status')
               ->setParameter('status', $status)
               ->getResult(\Doctrine\ORM\Query::HYDRATE_OBJECT)
               ;
    }

    public function findInvoicesByStatus(string $status): ?Array
    {
        $entityManager = $this->getEntityManager();
        return $entityManager->createQuery('SELECT invoice FROM App\Entity\Invoice invoice, App\Entity\Sneaker sneaker WHERE invoice.sneaker = sneaker.id AND invoice.paymentStatus = :status')
            ->setParameter('status', $status)
            ->getResult(\Doctrine\ORM\Query::HYDRATE_OBJECT)
            ;
    }

    public function findInvoicesFromSneakerType(string $status, bool $isFromShop = true): ?Array
    {
        $entityManager = $this->getEntityManager();
        return $entityManager->createQuery('SELECT invoice FROM App\Entity\Invoice invoice, App\Entity\Sneaker sneaker WHERE invoice.sneaker = sneaker.id AND sneaker.from_shop = :isFromShop AND invoice.paymentStatus = :status')
            ->setParameter('isFromShop', $isFromShop)
            ->setParameter('status', $status)
            ->getResult(\Doctrine\ORM\Query::HYDRATE_OBJECT)
            ;
    }

}
