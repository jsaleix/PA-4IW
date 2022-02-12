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
use App\Service\Payment\PaymentService;


#[Route('/shop')]
class ShopController extends AbstractController
{
    #[Route('/', name: 'shop_index', methods: ['GET'])]
    public function index(): Response
    {
        return $this->render('front/shop/index.html.twig', []);
    }

    #[Route('/checkout/{id}', name: 'shop_product_checkout', methods: ['GET'])]
    public function checkout( Sneaker $sneaker, Request $request, PaymentService $paymentService): Response
    {
        
        if( !$sneaker->getFromShop() || !$sneaker->getStripeProductId() ){
            return $this->redirectToRoute('front_default', [], Response::HTTP_SEE_OTHER);
        }

        if( !$this->getUser() ){
            return $this->redirectToRoute('app_login', [], Response::HTTP_SEE_OTHER);
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
