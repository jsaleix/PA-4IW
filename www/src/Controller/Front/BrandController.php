<?php

namespace App\Controller\Front;

use App\Entity\User;
use App\Entity\Brand;
use App\Repository\BrandRepository;
use App\Repository\SneakerRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Form\Front\UserType;

#[Route('/brands')]
class BrandController extends AbstractController
{
    #[Route('/', name: 'brand_index', methods: ['GET'])]
    public function index(BrandRepository $brandRepository): Response
    {
        return $this->render('front/brand/index.html.twig', [
            'brands' => $brandRepository->findAll()
        ]);
    }

    #[Route('/{id}', name: 'brand_show', methods: ['GET'])]
    public function show(Brand $brand, SneakerRepository $sneakerRepository): Response
    {
        return $this->render('front/brand/show.html.twig', [
            'brand' => $brand,
            'sneakers' => $sneakerRepository->findBy([ 'brand' => $brand->getId()])
        ]);
    }
}
