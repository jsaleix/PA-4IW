<?php

namespace App\Controller\Back;

use App\Controller\EntityManagerInterface;
use App\Entity\SneakerModel;
use App\Form\Back\SneakerModelType;
use App\Repository\SneakerModelRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/admin/models')]
class SneakerModelController extends AbstractController
{
    #[Route('/', name: 'back_sneaker_model_index', methods: ['GET'])]
    public function index(SneakerModelRepository $sneakerModelRepository): Response
    {
        return $this->render('sneaker_model/index.html.twig', [
            'sneaker_models' => $sneakerModelRepository->findAll(),
        ]);
    }

    #[Route('/new', name: 'back_sneaker_model_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $sneakerModel = new SneakerModel();
        $form = $this->createForm(SneakerModelType::class, $sneakerModel);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($sneakerModel);
            $entityManager->flush();

            return $this->redirectToRoute('app_sneaker_model_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('sneaker_model/new.html.twig', [
            'sneaker_model' => $sneakerModel,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'back_sneaker_model_show', methods: ['GET'])]
    public function show(SneakerModel $sneakerModel): Response
    {
        return $this->render('sneaker_model/show.html.twig', [
            'sneaker_model' => $sneakerModel,
        ]);
    }

    #[Route('/{id}/edit', name: 'back_sneaker_model_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, SneakerModel $sneakerModel, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(SneakerModelType::class, $sneakerModel);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('app_sneaker_model_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('sneaker_model/edit.html.twig', [
            'sneaker_model' => $sneakerModel,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'back_sneaker_model_delete', methods: ['POST'])]
    public function delete(Request $request, SneakerModel $sneakerModel, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$sneakerModel->getId(), $request->request->get('_token'))) {
            $entityManager->remove($sneakerModel);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_sneaker_model_index', [], Response::HTTP_SEE_OTHER);
    }
}
