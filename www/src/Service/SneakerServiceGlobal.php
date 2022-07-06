<?php
namespace App\Service;

use App\Entity\Invoice;
use App\Entity\Image;
use App\Entity\Sneaker;
use App\Repository\InvoiceRepository;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\EntityManagerInterface;
use Stripe\StripeClient;

class SneakerServiceGlobal
{
    public function __construct(
        private InvoiceRepository $invoiceRepository,
        private EntityManagerInterface $entityManager
    )
    {}

    public function hasActiveTransaction(Sneaker $sneaker): bool
    {
        if($sneaker->getFromShop()){
            return false;
        }else{
            return $this->hasActiveTransactionMP($sneaker);
        }
    }

    public function hasActiveTransactionMP(Sneaker $sneaker): bool
    {
        if($sneaker->getSold()){
            $invoice = $this->invoiceRepository->findOneBy([
                'sneaker' => $sneaker,
                'paymentStatus' => Invoice::FINISHED_STATUS
            ]);
            if($invoice !== null) return true;
        }

        return false;
    }

}