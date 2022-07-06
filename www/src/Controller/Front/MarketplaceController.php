<?php

namespace App\Controller\Front;

use App\Entity\Sneaker;
use App\Repository\SneakerRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Service\Payment\PaymentService;
use App\Security\Voter\SneakerVoter;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;

#[Route('/marketplace')]
class MarketplaceController extends AbstractController
{
    #[Route('/', name: 'marketplace_index', methods: ['GET'])]
    public function index(SneakerRepository $sneakerRepository, Request $request): Response
    {
        $params = ['from_shop' => false];
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
        return $this->render('front/marketplace/index.html.twig', [
            'sneakers' => $sneakers,
            'orderParam' => $orderParam,
            'statusParam' => $statusParam
        ]);
    }

    #[Route('/checkout/{id}', name: 'marketplace_product_checkout', methods: ['GET'])]
    #[IsGranted(SneakerVoter::BUY_FROM_MP, 'sneaker')]
    public function checkout( Sneaker $sneaker, Request $request, PaymentService $paymentService ): Response
    {
        if( !$this->getUser()->getAddress() || !$this->getUser()->getCity() ){
            $this->addFlash('warning', "You must specify the address and city to which you want to be delivered.");
            return $this->redirectToRoute('account_profile', [], Response::HTTP_SEE_OTHER);
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
