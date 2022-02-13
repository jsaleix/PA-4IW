<?php

namespace App\Controller\Front;

use App\Entity\Invoice;
use App\Entity\User;
use App\Entity\Sneaker;
use App\Form\SneakerType;
use App\Repository\InvoiceRepository;
use App\Repository\SneakerRepository;
use App\Security\Voter\SneakerVoter;
use App\Security\Voter\RoleVoter;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Doctrine\Persistence\ObjectManager;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

use Stripe\StripeClient;

class SneakersController extends AbstractController
{
    #[Route('/account/publish', name: 'front_account_sneaker_add')]
    public function addMP(Request $request)//Adding sneakers on the Marketplace
    {
        $user = $this->getUser();
        if( !in_array('ROLE_SELLER',  $user->getRoles()) ){
            return $this->redirectToRoute('front_account_index', [], Response::HTTP_SEE_OTHER);
        }

        $sneaker = new Sneaker();
        $form = $this->createForm(SneakerType::class, $sneaker);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $sneaker->setPublisher( $user);
            $sneaker->setFromShop( false );
            $sneaker->setUnused( true );
            $sneaker->setPublicationDate( new \DateTime() );

            $stripe = new StripeClient($_ENV['STRIPE_SK']);
            $sneakerId = $stripe->products->create([
                'name' => 'MP PRODUCT: ' . $sneaker->getName(),
            ]);
            if($sneakerId){
                $sneaker->setStripeProductId($sneakerId->id);
                $em->persist($sneaker);
                $em->flush();
            }

            return $this->redirectToRoute('default');
        }

        return $this->render('front/account/sneakers/new.html.twig', [
            'formSneaker' => $form->createView()
        ]);
    }

    #[Route('/account/your-sales', name: 'front_account_seller_sales')]
    public function SellerList(SneakerRepository $sneakerRepository){//Displays the products a seller user owns/is selling
        $user = $this->getUser();

        if( !in_array('ROLE_SELLER',  $user->getRoles()) ){
            return $this->redirectToRoute('front_account_index', [], Response::HTTP_SEE_OTHER);
        }

        //$sales = $invoiceRepository->findBy(['paymentStatus'=> Invoice::SOLD_STATUS]);
        $sales = $sneakerRepository->findUserSneakersByInvoiceStatus(Invoice::SOLD_STATUS, $user);
        //dd( $sales);
        return $this->render('front/account/sneakers/list.html.twig', [
            'sneakers' => $user->getPublishedSneakers(),
            'sales' => $sales
        ]);
    }

    #[Route('/account/sneaker/{id}', name: 'front_account_sneaker_edit', methods: ['GET', 'POST'])]
    #[IsGranted(SneakerVoter::EDIT, 'sneaker')]
    public function editSneaker(Request $request, Sneaker $sneaker)//Edit sneakers as seller
    {
        $user = $this->getUser();
        if( !in_array('ROLE_SELLER',  $user->getRoles()) ){
            return $this->redirectToRoute('front_account_index', [], Response::HTTP_SEE_OTHER);
        }

        $form = $this->createForm(SneakerType::class, $sneaker);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('front_account_seller_sales', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('front/account/sneakers/edit.html.twig', [
            'sneaker'=>$sneaker,
            'form' => $form->createView()
         ]);
    }

    #[Route('/sneaker/{id}', name: 'front_sneaker_item', methods: ['GET'])]
    public function sneakerPage( Sneaker $sneaker, InvoiceRepository $invoiceRepository)
    {
        $invoice = $invoiceRepository->findOneBy(['sneaker' => $sneaker]);
        if($invoice && $invoice->getPaymentStatus() === Invoice::SOLD_STATUS ){
            $sold = true;
        }else{
            $sold = false;
        }

        return $this->render('front/sneaker/sneaker.html.twig', [
            'sneaker'=>$sneaker,
            'sold' => $sold
        ]);
    }

}
