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

    #[Route('/checkout/{id}', name: 'shop_product_checkout', methods: ['GET'])]
    public function checkout( Sneaker $sneaker, Request $request ): Response
    {
        $buyer  = $this->getUser();

        if( !$sneaker->getFromShop() || !$sneaker->getStripeProductId() ){
            return $this->redirectToRoute('front_default', [], Response::HTTP_SEE_OTHER);
        }

        $stripe = new StripeClient($_ENV['STRIPE_SK']);
        $expires_at = ((new \DateTime())->modify('+1 hour'))->getTimeStamp();

        $price = $stripe->prices->create([
            'unit_amount' => $sneaker->getPrice()*100,
            'currency' => 'eur',
            'product' => $sneaker->getStripeProductId(),
        ]);

        $url = $stripe->checkout->sessions->create([
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
        
        header('Location:' . $url->url); 
        return $this->render('front/shop/checkout.html.twig', []);
    }
}
