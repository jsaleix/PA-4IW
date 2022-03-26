<?php

namespace App\Controller\Front;

use App\Entity\Invoice;
use App\Repository\UserRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Form\Front\UserType;

use Stripe\StripeClient;

#[Route('/account')]
class AccountController extends AbstractController
{
    #[Route('/', name: 'account_index', methods: ['GET'])]
    public function index(): Response
    {
        return $this->render('front/account/index.html.twig', []);
    }

    #[Route('/profile', name: 'account_profile', methods: ['GET', 'POST'])]
    public function profile(Request $request): Response
    {
        $user = $this->getUser();
        $form = $this->createForm(UserType::class, $user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->getDoctrine()->getManager()->flush();
            $user->setImageFile(null);
            return $this->redirectToRoute('front_account_profile', [], Response::HTTP_SEE_OTHER);
        }
        $user->setImageFile(null);
        return $this->render('front/account/profile/index.html.twig', [
            'user' => $user,
            'form' => $form->createView()
         ]);
    }

    #[Route('/orders', name: 'account_orders', methods: ['GET'])]
    public function orders(Request $request): Response
    {
        $user = $this->getUser();
        return $this->render('front/account/orders/index.html.twig', [
            'invoices' => $user->getInvoices()
        ]);
    }

    #[Route('/orders/{id}', name: 'account_order', methods: ['GET'])]
    public function order(Invoice $invoice): Response
    {
        return $this->render('front/account/orders/show.html.twig', [
            'invoice' => $invoice
        ]);
    }

    #[Route('/become_seller', name: 'account_become_seller', methods: ['GET'])]
    public function becomeSeller(Request $request): Response
    {
        $user = $this->getUser();
        if( in_array('ROLE_SELLER',  $user->getRoles()) ){
            return $this->redirectToRoute('front_account_index', [], Response::HTTP_SEE_OTHER);
        }

        $stripe = new StripeClient($_ENV['STRIPE_SK']);
        if($user->getStripeConnectId()){
            $account = $user->getStripeConnectId();
        }else{
            $account = $stripe->accounts->create([
                'type' => 'express'
            ]);

            if($account){
                $account = $account->id;
                $user->setStripeConnectId($account);
                $entityManager = $this->getDoctrine()->getManager();
                $entityManager->persist($user);
                $entityManager->flush();
            }
        }

        //Checking if stripe registration is over else redirect to form
        $status = $stripe->accounts->retrieve($account);
        //dd($status);
        if(/*!$status->charges_enabled*/ !$status->details_submitted){
            $link = $stripe->accountLinks->create([
                'account' => $account,
                'refresh_url' => 'http://localhost/account/become_seller',
                'return_url' => 'http://localhost/account/become_seller',
                'type' => 'account_onboarding',
            ]);
            
            header('Location:' . $link->url); 
        }else{
            $newRoles = $user->getRoles();
            $newRoles[] = 'ROLE_SELLER';

            $user->setRoles($newRoles);
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($user);
            $entityManager->flush();
            //return $this->redirectToRoute('front_index', [], Response::HTTP_SEE_OTHER);
        }

        
        return $this->render('front/account/becomeSeller/index.html.twig', []);
    }

    #[Route('/stripe-managing', name: 'account_manage_stripe', methods: ['GET'])]
    public function manageStripeAccount(Request $request): Response
    {
        $user = $this->getUser();
        if( !in_array('ROLE_SELLER',  $user->getRoles()) ){
            return $this->redirectToRoute('front_account_index', [], Response::HTTP_SEE_OTHER);
        }

        $stripe = new StripeClient($_ENV['STRIPE_SK']);
        if($user->getStripeConnectId()){
            $account = $user->getStripeConnectId();
        }

        $link = $stripe->accounts->createLoginLink(
            $user->getStripeConnectId()
        );

        header('Location:' . $link->url);
        return $this->render('front/account/becomeSeller/index.html.twig', []);
    }

}
