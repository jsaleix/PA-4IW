<?php

namespace App\Controller\Front;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Finder\Finder;
use App\Repository\CategoryRepository;
use App\Repository\BrandRepository;
use App\Repository\SneakerRepository;

class DefaultController extends AbstractController
{
    #[Route('/', name: 'default')]
    public function index(CategoryRepository $categoryRepository, BrandRepository $brandRepository, SneakerRepository $sneakerRepository): Response
    {        
        
        return $this->render('front/default/index.html.twig', [
            'categories' => $categoryRepository->findAll(),
            'brands' => $brandRepository->findBy(array(), array(), 4, 0),
            'popularSneakers' => $sneakerRepository->findBy(array(), array(), 4, 0),
        ]);
    }
}