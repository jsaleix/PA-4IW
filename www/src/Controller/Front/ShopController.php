<?php

namespace App\Controller\Front;

use App\Entity\User;
use App\Entity\Brand;
use App\Entity\Invoice;
use App\Entity\Sneaker;
use App\Repository\SneakerRepository;
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
    public function index(SneakerRepository $sneakerRepository, Request $request): Response
    {
        $params = ['from_shop' => true];
        $filter = ['id' => 'DESC'];

        $statusParam    = $request->query->get('status');
        $orderParam     = $request->query->get('order');

        if( $orderParam && in_array($orderParam, ['ASC', 'DESC'])){
            $filter['id'] = $orderParam;
        }

        if( $statusParam && in_array($statusParam, ['sold', 'buyable']) ){
            $params['sold'] = $statusParam === 'sold' ? true : null;
        }

        $sneakers = $sneakerRepository->findBy( $params, $filter);

        return $this->render('front/shop/index.html.twig', [
            'sneakers' => $sneakers,
            'orderParam' => $orderParam,
            'statusParam' => $statusParam
        ]);
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

        if( !$this->getUser()->getAddress() || !$this->getUser()->getCity() ){
            $this->addFlash('warning', "You must specify the address and city to which you want to be delivered.");
            return $this->redirectToRoute('account_profile', [], Response::HTTP_SEE_OTHER);
        }
        
        $buyer  = $this->getUser();
        $url = $paymentService->generatePaymentIntent($sneaker, $buyer, false);

        if($url){
            header('Location:' . $url);
        }else{
            return $this->redirectToRoute('front_default', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('front/shop/checkout.html.twig', []);
    }
}
