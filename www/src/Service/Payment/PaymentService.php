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

    /*
     * <--- Payment intents functions
     */
    public function generatePaymentIntent(Sneaker $sneaker, User $buyer, Request $request)
    {
        if($sneaker->getSold()){
            return false;
        }

        $localhost = $request->getHttpHost();
        $protocol   = $request->getScheme();
        $url        = "$protocol://$localhost";

        if(!$sneaker->getFromShop()){
            return $this->generateMPIntent($sneaker, $buyer, $url);
        }else{
            return $this->generateShopIntent($sneaker, $buyer, $url);
        }

    }

    public function generateMPIntent(Sneaker $sneaker, User $buyer, String $url){
        $invoice = $this->invoiceRepository->findOneBy(['sneaker' => $sneaker->getId()]);
        /*
         * Checking if there is already an invoice created with either
         * a successful status meaning it's already sold
         * or a buyer already affected, meaning someone might already be buying the product
         */
        if( $invoice
            && ($invoice->getPaymentStatus() === 'success' || $invoice->getBuyer() !== $buyer)
        ){
            return false;
        }

        $session = $this->generateStripeSession($sneaker, $url);
        $invoice = $this->invoiceRepository->findOneBy(['sneaker' => $sneaker->getId()]);

        if( !$invoice ){
            $invoice = new Invoice();
            $invoice->setSneaker($sneaker);
        }

        $invoice->setReceptionAddress($buyer->getAddress() . ' ' . $buyer->getCity());
        $invoice->setPaymentStatus(Invoice::PENDING_STATUS);
        $invoice->setBuyer($buyer);
        $invoice->setDate(new \DateTime());
        $invoice->setStripePI($session->payment_intent);
        $invoice->setPrice($sneaker->getPrice());

        $this->entityManager->persist($invoice);
        $this->entityManager->flush();

        return $session->url;
    }

    public function generateShopIntent(Sneaker $sneaker, User $buyer, String $url){

        $session = $this->generateStripeSession($sneaker, $url);
        $invoice = new Invoice();
        $invoice->setSneaker($sneaker);

        $invoice->setReceptionAddress($buyer->getAddress() . ' ' . $buyer->getCity());
        $invoice->setPaymentStatus(Invoice::PENDING_STATUS);
        $invoice->setBuyer($buyer);
        $invoice->setDate(new \DateTime());
        $invoice->setStripePI($session->payment_intent);
        $invoice->setPrice($sneaker->getPrice());

        $this->entityManager->persist($invoice);
        $this->entityManager->flush();

        return $session->url;
    }

    public function generateStripeSession(Sneaker $sneaker, String $url){

        $stripe     = new StripeClient($_ENV['STRIPE_SK']);
        $expires_at = ((new \DateTime())->modify('+1 hour'))->getTimeStamp();

        $price = $stripe->prices->create([
            'unit_amount' => $sneaker->getPrice()*100,
            'currency' => 'eur',
            'product' => $sneaker->getStripeProductId(),
        ]);

        $session = $stripe->checkout->sessions->create([
            'success_url' => "$url?success",
            'cancel_url' => "$url?failed",
            'line_items' => [
                [
                    'price' => $price->id,
                    'quantity' => 1,
                ],
            ],
            'expires_at' => $expires_at,
            'mode' => 'payment',
        ]);

        return $session;
    }
    /*
     * ---->
     */

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
            $currStock = $sneaker->getStock() - 1 ;
            $sneaker->setStock($currStock);

            //Checking sneaker stock and if stock arrives to 0, set Sold to true
            if($currStock === 0){
                $sneaker->setSold(true);
                //$this->entityManager->persist($sneaker);
                $this->entityManager->flush();
            }

            $invoice->setPaymentStatus(Invoice::SOLD_STATUS);
            //$this->entityManager->persist($invoice);
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
            $feesAmount = $invoice->getPrice()*$FEES;

            $stripe = new StripeClient($_ENV['STRIPE_SK']);
            $stripe->transfers->create([
                'amount' => ($invoice->getPrice() - $feesAmount)*100,
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