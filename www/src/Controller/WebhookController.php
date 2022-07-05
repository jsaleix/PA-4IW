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
use App\Service\Payment\SellerService;
use App\Service\Payment\PaymentService;

#[Route('/webhook')]
class WebhookController extends AbstractController
{
    #[Route('/stripe', name: 'stripe_listener', methods: ['POST'])]
    public function index(
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

    #[Route('/stripe/{id}', name: 'stripe_event_visualizer', requirements: ['id' => '^\d+$'], methods: ['GET'])]
    public function event(
        Request $request,
        $id,
        InvoiceRepository $invoiceRepository,
        LoggerInterface $logger
    ): Response
    {
        $logger->info('I just got the logger');

        $stripe= new StripeClient($_ENV['STRIPE_SK']);
        $event = $stripe->events->retrieve($id);
        $stripePI = $event['data']['object']['id'];
        //dd($stripePI);
        $invoice = $invoiceRepository->findOneBy(['stripePI' => $stripePI]);
        //dd($invoice);

        return new JsonResponse([$event]);
    }

    #[Route('/stripe/transfers', name: 'stripe_transfers_visualizer', methods: ['GET'])]
    public function transfersList(
        InvoiceRepository $invoiceRepository,
        PaymentService $paymentService
    ): Response
    {
        $invoice = $invoiceRepository->findOneBy(['id' => 13]);
        //$test = $paymentService->confirmPaymentMP($invoice);
        $stripe= new StripeClient($_ENV['STRIPE_SK']);
        $transfers = $stripe->transfers->all(['limit' => 3]);

        return new JsonResponse([]);
    }
}
