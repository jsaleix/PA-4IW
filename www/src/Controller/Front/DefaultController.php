<?php

namespace App\Controller\Front;

use App\Repository\SneakerModelRepository;
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
    public function index(CategoryRepository $categoryRepository,
                          BrandRepository $brandRepository,
                          SneakerRepository $sneakerRepository,
                          SneakerModelRepository $sneakerModelRepository
    ): Response
    {

        return $this->render('front/default/index.html.twig', [
            'categories' => $categoryRepository->findAll(),
            'brands' => $brandRepository->findBy(array(), array(), 4, 0),
            'popularSneakersFromShop' => $sneakerRepository->findBy(['from_shop' => true, 'sold' => null ], ['id' => 'DESC'], 4, 0),
            'popularSneakersFromMP' => $sneakerRepository->findBy(['from_shop' => false, 'sold' => null], ['id' => 'DESC'], 4, 0),
            'lastModels' => $sneakerModelRepository->findBy([], ['id' => 'DESC'], 4, 0)
        ]);
    }

    #[Route('/about-us', name: 'about-us')]
    public function aboutUs(): Response
    {
        return $this->render('front/default/about-us.html.twig');
    }
    
    #[Route('/search')]
    public function findSneakersOverall(SneakerRepository $repository)
    {
        $sneakers = $repository -> findSearch();
        return $this ->render('front/search/index.html.twig', [
            'sneakers' => $sneakers,
        ]);
    }
}
