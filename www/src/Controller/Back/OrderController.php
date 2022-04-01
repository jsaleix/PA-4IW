<?php

namespace App\Controller\Back;

use App\Entity\Invoice;
use App\Form\Front\Invoice\TrackingNumberFormType;
use App\Repository\SneakerRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/admin/orders')]
class OrderController extends AbstractController
{
    #[Route('/', name: 'admin_orders_index')]
    public function index(SneakerRepository $sneakerRepository): Response
    {
        return $this->render('back/orders/index.html.twig', []);
    }

    #[Route('/{id}', name: 'admin_orders_show')]
    public function show(Invoice $invoice, Request $request, EntityManagerInterface $entityManager, SneakerRepository $sneakerRepository): Response
    {
        $canSetTrackingNb = $invoice->getPaymentStatus() === Invoice::SOLD_STATUS;
        if($canSetTrackingNb){
            $form = $this->createForm(TrackingNumberFormType::class, $invoice);
            $form->handleRequest($request);

            if( $form->isSubmitted() && $form->isValid() ) {
                $invoice->setPaymentStatus(Invoice::DELIVERING_STATUS);
                $entityManager->flush();
            }
            $canSetTrackingNb = $invoice->getPaymentStatus() === Invoice::SOLD_STATUS;
        }
        return $this->render('back/orders/show.html.twig', [
            'invoice' => $invoice,
            'form' => $canSetTrackingNb ? $form->createView() : null
        ]);
    }

}
