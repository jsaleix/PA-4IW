<?php

namespace App\Controller\Back;

use App\Entity\Sneaker;
use App\Form\Back\SneakerType;
use App\Repository\InvoiceRepository;
use App\Repository\SneakerRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

use Doctrine\ORM\EntityManagerInterface;

#[Route('/admin/sneakers')]
class AdminSneakerController extends AbstractController
{

    #[Route('/panel', name: 'admin_sneaker_dashboard', methods: ['GET'])]
    public function index(SneakerRepository $sneakerRepository): Response
    {
        return $this->render('back/admin_sneaker/index.html.twig', [
            'sneakers' => $sneakerRepository->findAll(),
        ]);
    }
    #[Route('/{id}', name: 'admin_sneaker_show', methods: ['GET'])]
    public function show(Sneaker $sneaker, InvoiceRepository $invoiceRepository): Response
    {
        $invoices = $invoiceRepository->findBy(['sneaker' => $sneaker]);

        return $this->render('back/admin_sneaker/show.html.twig', [
            'sneakers' => $sneaker,
            'invoices' => $invoices
        ]);
    }

    #[Route('/{id}/edit', name: 'admin_sneaker_edit', methods: ['GET','POST'])]
    public function edit(Request $request, Sneaker $sneaker, EntityManagerInterface $entityManager): Response
    {
        $lastImgs = $sneaker->getImages();

        $form = $this->createForm(SneakerType::class, $sneaker);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            if($sneaker->getStock() > 1 && $sneaker->getSold()){
                $sneaker->setSold(null);
            }
            $entityManager->flush();
            return $this->redirectToRoute('admin_sneaker_dashboard', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('back/admin_sneaker/edit.html.twig', [
            'sneakers' => $sneaker,
            'form' => $form->createView()
         ]);
    }

    #[Route('/{id}', name: 'admin_sneaker_delete', methods: ['POST'])]
    public function delete(Request $request, Sneaker $sneaker, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$sneaker->getId(), $request->request->get('_token'))) {
            $entityManager->remove($sneaker);
            $entityManager->flush();
        }

        return $this->redirectToRoute('admin_sneaker_dashboard', [], Response::HTTP_SEE_OTHER);
    }
}
