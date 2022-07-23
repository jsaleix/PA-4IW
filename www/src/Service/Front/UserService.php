<?php

namespace App\Service\Front;

use App\Entity\User;
use App\Entity\Invoice;
use App\Entity\Sneaker;
use App\Repository\UserRepository;
use App\Repository\SneakerRepository;
use Doctrine\ORM\EntityManagerInterface;
use phpDocumentor\Reflection\Types\Boolean;
use Psr\Log\LoggerInterface;
use Stripe\StripeClient;
use App\Repository\InvoiceRepository;
use Symfony\Component\HttpFoundation\Request;

class UserService
{
    public function __construct(
        private UserRepository $userRepository,
        private EntityManagerInterface $entityManager,
        private LoggerInterface $logger,
        private SneakerRepository $sneakerRepository,
        private InvoiceRepository $invoiceRepository

    ) {}

    public function waitingReceivingFromUser(User $user): bool
    {
        $invoices = $this->invoiceRepository->findBy([
            'paymentStatus' => Invoice::DELIVERING_STATUS,
            'buyer' => $user
        ]);
        
        if( $invoices ) return true;
        return false;
    }
}