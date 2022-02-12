<?php

namespace App\Controller;

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

#[Route('/webhook')]
class WebhookController extends AbstractController
{
    #[Route('/stripe', name: 'stripe_listener', methods: ['POST'])]
    public function index(
        Request $request, 
        UserRepository $userRepository,
        InvoiceRepository $invoiceRepository,
    ): Response
    {
        $stripe= new StripeClient($_ENV['STRIPE_SK']);
        $webhookSecret = $_ENV['STRIPE_WH_SK'];

        $event = $request->query;
        $signature = $request->headers->get('stripe-signature');

        $entityManager = $this->getDoctrine()->getManager();

        try{
            if( !$webhookSecret) { throw new \Exception('Missing secret'); }
            $event = \Stripe\Webhook::constructEvent(
                $request->getcontent(),
                $signature,
                $webhookSecret
            );

            $user = $userRepository->findOneBy(['id' => 20]);
            $user->setName( $event['data']['object'] ['id']);
            $entityManager->persist($user);
            $entityManager->flush();

            $type   = $event['type'];
            $object = $event['data']['object'];
            $entityManager = $this->getDoctrine()->getManager();

            $invoice = $invoiceRepository->findOneBy(['stripePI' => $object['id']]);

            switch ($type) {
                case 'payment_intent.succeeded':
                    //create bill
                    $invoice->setPaymentStatus('success');
                    $entityManager->persist($invoice);
                    $entityManager->flush();

                    break;

                // case 'checkout.session.completed':
                //     break;
    
                case 'payment_intent.canceled':
                    $invoice->setPaymentStatus(null);
                    $invoice->setBuyer(null);
                    $invoice->setDate(null);
                    $entityManager->persist($invoice);
                    $entityManager->flush();
                    break;
                    
                default:
                    throw new \Exception('Unhandled event');
            }
            
            $response = new Response();
            $response->setStatusCode(Response::HTTP_OK);
            return $response;
            
            //return new JsonResponse([['status' => 200], 403, [], true]);

        } catch (\Exception $e) {
            $response = new Response();
            $response->setStatusCode(Response::HTTP_FORBIDDEN);
            return $response;
        }
    }

    #[Route('/stripe/{id}', name: 'stripe__event_visualizer', methods: ['GET'])]
    public function event(
        Request $request,
        $id,
        InvoiceRepository $invoiceRepository,
        LoggerInterface $logger
    ): Response
    {
        $logger->info('I just got the logger');

        $stripe= new StripeClient($_ENV['STRIPE_SK']);
        $event = $stripe->events->retrieve(
            $id,
            []
        );
        $stripePI = $event['data']['object']['id'];
        //dd($stripePI);
        $invoice = $invoiceRepository->findOneBy(['stripePI' => $stripePI]);
        //dd($invoice);

        return new JsonResponse([$event]);
    }
}
