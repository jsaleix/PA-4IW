<?php

namespace App\Controller;

use Doctrine\ORM\EntityManagerInterface;
use Stripe\Webhook;
use App\Entity\User;
use Stripe\StripeClient;
use App\Repository\UserRepository;
use App\Repository\InvoiceRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Psr\Log\LoggerInterface;
use App\Entity\Invoice;
use App\Service\Front\SellerService;
use App\Service\Payment\PaymentService;

#[Route('/webhook')]
class WebhookController extends AbstractController
{

    const ACCOUNT_EVENT_TYPES = ['payment_intent.succeeded', 'checkout.session.expired', 'payment_intent.canceled'];
    const CONNECT_EVENT_TYPES = ['account.updated', 'capability.updated'];

    #[Route('/stripe/dev', name: 'stripe_listener_dev', methods: ['POST'])]
    public function dev(
        Request $request, 
        UserRepository $userRepository,
        InvoiceRepository $invoiceRepository,
        EntityManagerInterface $entityManager,
        PaymentService $paymentService,
        SellerService $sellerService,
        LoggerInterface $logger
    ): Response
    {
        try{
            //$stripe= new StripeClient($_ENV['STRIPE_SK']);
            $webhookSecret = $_ENV['STRIPE_WH_SK'];
            $signature = $request->headers->get('stripe-signature');

            if( !$webhookSecret) { throw new \Exception('Missing secret'); }
            $event = \Stripe\Webhook::constructEvent(
                $request->getcontent(),
                $signature,
                $webhookSecret
            );

            $type   = $event['type'];
            $object = $event['data']['object'];

            switch ($type) {
                case 'payment_intent.succeeded':
                    //Retrieving invoice
                    $invoice = $invoiceRepository->findOneBy(['stripePI' => $object['id']]);
                    if(!$invoice) throw new \Exception('No invoice linked');
                    //creates bill
                    $paymentService->confirmPayment($invoice);
                    break;

                case 'checkout.session.expired':
                case 'payment_intent.canceled':
                    //Retrieving invoice
                    $invoice = $invoiceRepository->findOneBy(['stripePI' => $object['id']]);
                    if(!$invoice) throw new \Exception('No invoice linked');
                    $paymentService->removeInvoice($invoice);
                    break;

                case 'account.updated':
                case 'capability.updated':
                    $sellerService->updateSellerCapabilities($event);
                    break;
                
                default:
                    throw new \Exception('Unhandled event');
            }
            
            $response = new Response();
            $response->setStatusCode(Response::HTTP_OK);
            return $response;
            
        } catch (\Exception $e) {
            $logger->error($e);
            $response = new Response();
            $response->setStatusCode(Response::HTTP_FORBIDDEN);
            return $response;
        }
    }

    #[Route('/stripe/connect', name: 'stripe_listener_connect', methods: ['POST'])]
    public function connectEvent(
        Request $request, 
        UserRepository $userRepository,
        InvoiceRepository $invoiceRepository,
        EntityManagerInterface $entityManager,
        PaymentService $paymentService,
        SellerService $sellerService,
        LoggerInterface $logger
    ): Response
    {
        try{
            //$stripe= new StripeClient($_ENV['STRIPE_SK']);
            $webhookSecret = $_ENV['STRIPE_WH_SK_CONNECT'];
            $signature = $request->headers->get('stripe-signature');

            if( !$webhookSecret) { throw new \Exception('Missing secret'); }
            $event = \Stripe\Webhook::constructEvent(
                $request->getcontent(),
                $signature,
                $webhookSecret
            );

            $type   = $event['type'];
            $object = $event['data']['object'];

            switch ($type) {
                case 'account.updated':
                case 'capability.updated':
                    $sellerService->updateSellerCapabilities($event);
                    break;
                
                default:
                    throw new \Exception('Unhandled event');
            }
            
            $response = new Response();
            $response->setStatusCode(Response::HTTP_OK);
            return $response;
            
        } catch (\Exception $e) {
            $logger->error($e);
            $response = new Response();
            $response->setStatusCode(Response::HTTP_FORBIDDEN);
            return $response;
        }
    }

    #[Route('/stripe/account', name: 'stripe_listener_account', methods: ['POST'])]
    public function account(
        Request $request, 
        UserRepository $userRepository,
        InvoiceRepository $invoiceRepository,
        EntityManagerInterface $entityManager,
        PaymentService $paymentService,
        SellerService $sellerService,
        LoggerInterface $logger
    ): Response
    {
        try{
            //$stripe= new StripeClient($_ENV['STRIPE_SK']);
            $webhookSecret = $_ENV['STRIPE_WH_SK'];
            $signature = $request->headers->get('stripe-signature');

            if( !$webhookSecret) { throw new \Exception('Missing secret'); }
            $event = \Stripe\Webhook::constructEvent(
                $request->getcontent(),
                $signature,
                $webhookSecret
            );

            $type   = $event['type'];
            $object = $event['data']['object'];

            switch ($type) {
                case 'payment_intent.succeeded':
                    //Retrieving invoice
                    $invoice = $invoiceRepository->findOneBy(['stripePI' => $object['id']]);
                    if(!$invoice) throw new \Exception('No invoice linked');
                    //creates bill
                    $paymentService->confirmPayment($invoice);
                    break;

                case 'checkout.session.expired':
                case 'payment_intent.canceled':
                    //Retrieving invoice
                    $invoice = $invoiceRepository->findOneBy(['stripePI' => $object['id']]);
                    if(!$invoice) throw new \Exception('No invoice linked');
                    $paymentService->removeInvoice($invoice);
                    break;
                
                default:
                    throw new \Exception('Unhandled event');
            }
            
            $response = new Response();
            $response->setStatusCode(Response::HTTP_OK);
            return $response;
            
        } catch (\Exception $e) {
            $logger->error($e);
            $response = new Response();
            $response->setStatusCode(Response::HTTP_FORBIDDEN);
            return $response;
        }
    }

}
