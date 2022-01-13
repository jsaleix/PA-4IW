<?php

namespace App\Controller\Back;

use App\Entity\Color;
use App\Form\ColorType;
use App\Repository\ColorRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/admin/ressources/colors')]
class ColorController extends AbstractController
{
    #[Route('/', name: 'color_index', methods: ['GET'])]
    public function index(ColorRepository $colorRepository): Response
    {
        return $this->render('back/color/index.html.twig', [
            'colors' => $colorRepository->findAll(),
        ]);
    }

    #[Route('/new', name: 'color_new', methods: ['GET','POST'])]
    public function new(Request $request): Response
    {
        $color = new Color();
        $form = $this->createForm(ColorType::class, $color);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($color);
            $entityManager->flush();

            return $this->redirectToRoute('color_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('back/color/new.html.twig', [
            'color' => $color,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'color_show', methods: ['GET'])]
    public function show(Color $color): Response
    {
        return $this->render('back/color/show.html.twig', [
            'color' => $color,
        ]);
    }

    #[Route('/{id}/edit', name: 'color_edit', requirements: ['id' => '^\d+$'], methods: ['GET','POST'])]
    public function edit(Request $request, Color $color): Response
    {
        $form = $this->createForm(ColorType::class, $color);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('color_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('back/color/edit.html.twig', [
            'color' => $color,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'color_delete', methods: ['POST'])]
    public function delete(Request $request, Color $color): Response
    {
        if ($this->isCsrfTokenValid('delete'.$color->getId(), $request->request->get('_token'))) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($color);
            $entityManager->flush();
        }

        return $this->redirectToRoute('color_index', [], Response::HTTP_SEE_OTHER);
    }
}
