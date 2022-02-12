<?php

namespace App\Controller\Front;

use App\Entity\User;
use App\Entity\Brand;
use App\Entity\Sneaker;
use App\Repository\BrandRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Service\Payment\PaymentService;

use Stripe\StripeClient;

#[Route('/marketplace')]
class MarketplaceController extends AbstractController
{
    #[Route('/', name: 'marketplace_index', methods: ['GET'])]
    public function index(): Response
    {
        return $this->render('front/marketplace/index.html.twig', []);
    }

    #[Route('/checkout/{id}', name: 'marketplace_product_checkout', methods: ['GET'])]
    public function checkout( Sneaker $sneaker, Request $request, PaymentService $paymentService ): Response
    {
        if( $sneaker->getFromShop() || !$sneaker->getStripeProductId() ){
            return $this->render('front/marketplace/index.html.twig', []);
        }

        if( !$this->getUser() ){
            return $this->redirectToRoute('app_login', [], Response::HTTP_SEE_OTHER);
        }

        $seller = $sneaker->getPublisher();

        if(!$seller->getStripeConnectId() ){
            return $this->render('front/marketplace/index.html.twig', []);
        }

        $buyer  = $this->getUser();
        $url = $paymentService->generatePaymentIntent($sneaker, $buyer);

        if($url){
            header('Location:' . $url);
        }else{
            return $this->redirectToRoute('front_default', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('front/shop/checkout.html.twig', []);
    }

}
