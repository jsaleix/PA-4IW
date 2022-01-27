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
    public function addMP(Request $request)
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
}
