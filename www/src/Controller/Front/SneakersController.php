<?php

namespace App\Controller\Front;

use App\Entity\Sneaker;
use App\Form\SneakerType;
use Doctrine\Persistence\ObjectManager;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

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
            $em->persist($sneaker);
            $em->flush();

            return $this->redirectToRoute('default');
        }

        return $this->render('front/account/sneakers/new.html.twig', [
            'formSneaker' => $form->createView()
        ]);
    }

    #[Route('/account/your-sales', name: 'front_account_seller_sales')]
    public function SellerList(){//Displays the products a seller user owns/is selling
        $user = $this->getUser();

        if( !in_array('ROLE_SELLER',  $user->getRoles()) ){
            return $this->redirectToRoute('front_account_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('front/account/sneakers/list.html.twig', [
            'sneakers' => $user->getPublishedSneakers(),
        ]);
    }

    #[Route('/account/sneaker/{id}', name: 'front_account_sneaker_edit', methods: ['GET', 'POST'])]
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
    public function sneakerPage( Sneaker $sneaker )
    {
        return $this->render('front/sneaker/sneaker.html.twig', [
            'sneaker'=>$sneaker,
        ]);
    }

}
