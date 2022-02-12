<?php

namespace App\Controller\Front;

use App\Entity\User;
use App\Entity\Brand;
use App\Entity\Invoice;
use App\Entity\Sneaker;
use Stripe\StripeClient;
use App\Form\Front\UserType;
use App\Repository\BrandRepository;
use App\Repository\InvoiceRepository;
use Symfony\Component\HttpFoundation\Request;

use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

#[Route('/shop')]
class ShopController extends AbstractController
{
    #[Route('/', name: 'shop_index', methods: ['GET'])]
    public function index(): Response
    {
        return $this->render('front/shop/index.html.twig', []);
    }

    #[Route('/checkout/{id}', name: 'shop_product_checkout', methods: ['GET'])]
    public function checkout( Sneaker $sneaker, Request $request, InvoiceRepository $invoiceRepository ): Response
    {
        
        if( !$sneaker->getFromShop() || !$sneaker->getStripeProductId() ){
            return $this->redirectToRoute('front_default', [], Response::HTTP_SEE_OTHER);
        }

        if( !$this->getUser() ){
            return $this->redirectToRoute('app_login', [], Response::HTTP_SEE_OTHER);
        }
        
        $buyer  = $this->getUser();
        $entityManager = $this->getDoctrine()->getManager();

        $invoice = $invoiceRepository->findOneBy(['sneaker' => $sneaker->getId()]);
        if( $invoice 
            && ($invoice->getPaymentStatus() === 'success' || $invoice->getBuyer() !== $buyer)
        ){
            return $this->redirectToRoute('front_default', [], Response::HTTP_SEE_OTHER);
        }

        $stripe = new StripeClient($_ENV['STRIPE_SK']);
        $expires_at = ((new \DateTime())->modify('+1 hour'))->getTimeStamp();

        $price = $stripe->prices->create([
            'unit_amount' => $sneaker->getPrice()*100,
            'currency' => 'eur',
            'product' => $sneaker->getStripeProductId(),
        ]);

        $session = $stripe->checkout->sessions->create([
            'success_url' => 'http://localhost?success',
            'cancel_url' =>  'http://localhost?failed',
            'line_items' => [
              [
                'price' => $price->id,
                'quantity' => 1,
              ],
            ],
            'expires_at' => $expires_at,
            'mode' => 'payment',
        ]);
        
        if( !$invoice ){
            $invoice = new Invoice();
            $invoice->setSneaker($sneaker);
        };

        $invoice->setPaymentStatus('pending');
        $invoice->setBuyer($buyer);
        $invoice->setDate(new \DateTime());
        $invoice->setStripePI($session->payment_intent);

        $entityManager->persist($invoice);
        $entityManager->flush();

        header('Location:' . $session->url); 
        return $this->render('front/shop/checkout.html.twig', []);
    }
}
