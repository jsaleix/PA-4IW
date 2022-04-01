<?php

namespace App\Controller\Back;

use App\Repository\SneakerRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/admin/shop')]
class AdminShopController extends AbstractController
{
    #[Route('/', name: 'admin_shop_index')]
    public function index(SneakerRepository $sneakerRepository): Response
    {
        $params = ['from_shop' => true];
        $filters = ['id' => 'DESC'];

        $sneakers = $sneakerRepository->findBy( $params, $filters);
        return $this->render('back/shop/index.html.twig', [
            'sneaker'=>$sneakers
        ]);
    }
}
