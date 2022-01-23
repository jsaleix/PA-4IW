<?php

namespace App\Controller\Front;

use App\Entity\User;
use App\Entity\Brand;
use App\Repository\BrandRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Form\Front\UserType;

#[Route('/marketplace')]
class MarketplaceController extends AbstractController
{
    #[Route('/', name: 'marketplace_index', methods: ['GET'])]
    public function index(): Response
    {
        return $this->render('front/marketplace/index.html.twig', []);
    }

}
