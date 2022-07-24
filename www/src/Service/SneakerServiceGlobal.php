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
            /**
             * Looking for an invoice with this sneaker and status "sold"
             * If this is the case, the sneaker has no active transaction
             */
            $invoice = $this->invoiceRepository->findOneBy([
                'sneaker' => $sneaker,
                'paymentStatus' => Invoice::FINISHED_STATUS
            ]);
            if(!$invoice) return true;
        }

        return false;
    }

}