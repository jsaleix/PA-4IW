<?php

namespace App\Controller\Back;

use App\Repository\SneakerRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/admin/marketplace')]
class AdminMarketPlaceController extends AbstractController
{
    #[Route('/', name: 'admin_marketplace_index')]
    public function index(SneakerRepository $sneakerRepository): Response
    {
        $params = ['from_shop' => false];
        $filters = ['id' => 'DESC'];

        $sneakers = $sneakerRepository->findBy( $params, $filters);
        return $this->render('back/marketplace/index.html.twig', [
            'sneaker'=>$sneakers
        ]);
    }
}
