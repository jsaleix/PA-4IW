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
    #[Route('/add', name: 'sneaker')]
    public function newSneaker(Request $request)
    {
        $sneaker = new Sneaker();

        $form = $this->createForm(SneakerType::class, $sneaker);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($sneaker);
            $em->flush();

            return $this->redirectToRoute('default');
        }

        return $this->render('front/addSneaker.html.twig', [
            'formSneaker' => $form->createView()
        ]);
    }
}
