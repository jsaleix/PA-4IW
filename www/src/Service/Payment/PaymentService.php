<?php

namespace App\Service\Payment;

use App\Entity\User;
use App\Entity\Invoice;
use App\Entity\Sneaker;
use Doctrine\ORM\EntityManagerInterface;
use phpDocumentor\Reflection\Types\Boolean;
use Stripe\StripeClient;
use App\Repository\InvoiceRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class PaymentService
{
    public function __construct(
        private InvoiceRepository $invoiceRepository,
        private EntityManagerInterface $entityManager
    ) {}

    public function generatePaymentIntent(Sneaker $sneaker, User $buyer)
    {

        $invoice = $this->invoiceRepository->findOneBy(['sneaker' => $sneaker->getId()]);
        /*
         * Checking if there is an invoice already created with either
         * a successful status meaning it's already sold
         * or a buyer already affected, meaning someone might already be buying the product
         */
        if( $invoice && $invoice->getBuyer()
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

        $invoice->setPaymentStatus(Invoice::PENDING_STATUS);
        $invoice->setBuyer($buyer);
        $invoice->setDate(new \DateTime());
        $invoice->setStripePI($session->payment_intent);

        $this->entityManager->persist($invoice);
        $this->entityManager->flush();

        return $session->url;
    }

    public function confirmPayment(Invoice $invoice){
        $sneaker = $invoice->getSneaker();
        if($sneaker->getFromShop()){
            $this->confirmPaymentShop($invoice, $sneaker);
        }else{
            $this->confirmPaymentMP($invoice, $sneaker);
        }
    }

    public function removeInvoice(Invoice $invoice){
        $this->entityManager->remove($invoice);
        $this->entityManager->flush();
    }

    public function confirmPaymentShop(Invoice $invoice, Sneaker $sneaker){
        try{
            //Checking sneaker stock and if stock arrives to 0, set Sold to true
            /*$sneaker->setSold(true);
            $this->entityManager->persist($sneaker);
            $this->entityManager->flush();*/

            $invoice->setPaymentStatus(Invoice::SOLD_STATUS);
            $this->entityManager->persist($invoice);
            $this->entityManager->flush();
            return true;
        }catch(\Exception $e){
            return false;
        }
    }

    public function confirmPaymentMP( Invoice $invoice, Sneaker $sneaker){
        try{
            $FEES = 0.1;

            //Updating sold attribute on sneaker
            $sneaker->setSold(true);
            $this->entityManager->persist($sneaker);
            $this->entityManager->flush();

            $invoice->setPaymentStatus(Invoice::SOLD_STATUS);
            $this->entityManager->persist($invoice);
            $this->entityManager->flush();

            //transfer funds from our Stripe account to the user connected account
            $sneaker = $invoice->getSneaker();
            $seller = $sneaker->getPublisher();
            if( !$seller->getStripeConnectId() ) throw new \Exception('No stripe connect id set');
            $feesAmount = $sneaker->getPrice()*$FEES;

            $stripe = new StripeClient($_ENV['STRIPE_SK']);
            $stripe->transfers->create([
                'amount' => ($sneaker->getPrice() - $feesAmount)*100,
                'currency' => 'eur',
                'destination' => $seller->getStripeConnectId(),
                'description' => 'Your sale on SNKERS - '.$sneaker->getName(),
            ]);
            return true;
        }catch(\Exception $e){
            return $e->getMessage();
        }
    }


}