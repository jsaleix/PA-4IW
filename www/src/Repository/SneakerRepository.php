<?php

namespace App\Repository;

use App\Datas\SearchData;
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

    /**
     * @param SearchData $search
     * @return Sneaker[]
     */
    public function findSearch(SearchData $search): array
    {
        $query = $this
            ->createQueryBuilder('p')
            ->select('c', 'p')
            ->join('p.brand', 'c');
        if (!empty($search-> q)) {
            $query = $query
                -> andWhere('p.name LIKE :q')
                ->setParameter('q', "%{$search-> q}%");
        }

        if (!empty($search->min)) {
            $query = $query
                ->andWhere('p.price >= :min')
                ->setParameter('min', $search->min);
        }

        if (!empty($search->max)) {
            $query = $query
                ->andWhere('p.price <= :max')
                ->setParameter('max', $search->max);
        }

        if (!is_null($search->fromShop)) {
            $query = $query
                ->andWhere('p.from_shop = (:fromShop)')
                ->setParameter('fromShop', $search->fromShop);
        }

        if (!empty($search->categories)) {
            $query = $query
                ->andWhere('c.id IN (:sneaker)')
                ->setParameter('sneaker', $search->categories);
        }

         return $query->getQuery()->getResult();
    }
}
