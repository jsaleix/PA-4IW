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
use App\Form\Front\UserType;

use Stripe\StripeClient;

#[Route('/shop')]
class ShopController extends AbstractController
{
    #[Route('/', name: 'shop_index', methods: ['GET'])]
    public function index(): Response
    {
        return $this->render('front/shop/index.html.twig', []);
    }

    #[Route('/shop/checkout/{id}', name: 'shop_product_checkout', methods: ['GET'])]
    public function checkout( Sneaker $sneaker, Request $request ): Response
    {
        if( !$sneaker->getFromShop() ){
            return $this->render('front/shop/index.html.twig', []);
        }
        $buyer  = $this->getUser();
        
        if( !$sneaker->getStripeProductId() ){
            return $this->render('front/shop/index.html.twig', []);
        }

        $stripe = new StripeClient($_ENV['STRIPE_SK']);

        $price = $stripe->prices->create([
            'unit_amount' => $sneaker->getPrice()*100,
            'currency' => 'eur',
            'product' => $sneaker->getStripeProductId(),
        ]);
                
        $url = $stripe->checkout->sessions->create([
            'success_url' => 'http://localhost/marketplace?success',
            'cancel_url' => 'http://localhost/marketplace?failed',
            'line_items' => [
              [
                'price' => $price->id,
                'quantity' => 1,
              ],
            ],
            'mode' => 'payment',
        ]);
        
        header('Location:' . $url->url); 
        return $this->render('front/shop/checkout.html.twig', []);
    }
}
