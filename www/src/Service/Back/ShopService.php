<?php

namespace App\Service\Back;

use App\Entity\User;
use App\Entity\Invoice;
use App\Entity\Sneaker;

use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use App\Repository\SneakerRepository;
use Psr\Log\LoggerInterface;
use App\Repository\InvoiceRepository;

use Stripe\StripeClient;

class ShopService
{
    public function __construct(
        private UserRepository $userRepository,
        private EntityManagerInterface $entityManager,
        private LoggerInterface $logger,
        private SneakerRepository $sneakerRepository,
        private InvoiceRepository $invoiceRepository

    ) {}

    public function waitingActionFromShop(): bool
    {
        $invoices = $this->invoiceRepository->findAll([
            'status' => Invoice::SOLD_STATUS
        ]);

        if( $invoices ) return true;
        
        return false;
    }
    
}