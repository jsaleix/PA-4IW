<?php

namespace App\Controller;

use Stripe\Webhook;
use App\Entity\User;
use Stripe\StripeClient;
use App\Repository\UserRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

#[Route('/webhook')]
class WebhookController extends AbstractController
{
    #[Route('/stripe', name: 'stripe_listener', methods: ['POST'])]
    public function index(Request $request, UserRepository $userRepository): Response
    {
        $stripe= new StripeClient($_ENV['STRIPE_SK']);
        $webhookSecret = $_ENV['STRIPE_WH_SK'];

        $event = $request->query;
        $signature = $request->headers->get('stripe-signature');

        try{
            if( !$webhookSecret) { throw new \Exception('Missing secret'); }
            $event = \Stripe\Webhook::constructEvent(
                $request->getcontent(),
                $signature,
                $webhookSecret
            );

            $type   = $event['type'];
            $object = $event['data']['object'];
            $entityManager = $this->getDoctrine()->getManager();
    
            switch ($type) {
            case 'payment_intent.succeeded':
                //create bill
                break;

              case 'checkout.session.completed':
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
            //return new JsonResponse([['error' => $e->getMessage(), 'status' => 403, [], true], 403]);
        }
    }
}
