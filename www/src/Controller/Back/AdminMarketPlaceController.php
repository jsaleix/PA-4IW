<?php

namespace App\Controller\Back;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/admin/marketplace')]
class AdminMarketPlaceController extends AbstractController
{
    #[Route('/', name: 'admin_marketplace_index')]
    public function index(): Response
    {
        return $this->render('back/marketplace/index.html.twig', []);
    }
}
