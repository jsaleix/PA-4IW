<?php

namespace App\Service\Payment;

use App\Entity\User;
use App\Entity\Invoice;
use App\Entity\Sneaker;
use Doctrine\ORM\EntityManagerInterface;
use Stripe\StripeClient;
use App\Repository\InvoiceRepository;
use Symfony\Component\HttpFoundation\Request;

use Symfony\Component\HttpFoundation\Response;

class PaymentService
{
    public function __construct(private InvoiceRepository $invoiceRepository, private EntityManagerInterface $entityManager) {}

    public function generatePaymentIntent(Sneaker $sneaker, User $buyer)
    {

        $invoice = $this->invoiceRepository->findOneBy(['sneaker' => $sneaker->getId()]);
        /*
         * Checking if there is an invoice already created with either
         * a successful status meaning it's already sold
         * or a buyer already affected, meaning someone might already be buying the product
         */
        if( $invoice
            && ($invoice->getPaymentStatus() === 'success' || $invoice->getBuyer() !== $buyer)
        ){
            return false;
        }

        $stripe = new StripeClient($_ENV['STRIPE_SK']);
        $expires_at = ((new \DateTime())->modify('+1 hour'))->getTimeStamp();

        $price = $stripe->prices->create([
            'unit_amount' => $sneaker->getPrice()*100,
            'currency' => 'eur',
            'product' => $sneaker->getStripeProductId(),
        ]);

        $session = $stripe->checkout->sessions->create([
            'success_url' => 'http://localhost?success',
            'cancel_url' =>  'http://localhost?failed',
            'line_items' => [
                [
                    'price' => $price->id,
                    'quantity' => 1,
                ],
            ],
            'expires_at' => $expires_at,
            'mode' => 'payment',
        ]);

        if( !$invoice ){
            $invoice = new Invoice();
            $invoice->setSneaker($sneaker);
        };

        $invoice->setPaymentStatus('pending');
        $invoice->setBuyer($buyer);
        $invoice->setDate(new \DateTime());
        $invoice->setStripePI($session->payment_intent);

        $this->entityManager->persist($invoice);
        $this->entityManager->flush();

        return $session->url;

    }
}