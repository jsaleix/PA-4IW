<?php

namespace App\Controller\Back;

use App\Entity\Category;
use App\Repository\SneakerRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class CategoryController extends AbstractController
{
    #[Route('/', name: 'default', methods: ['GET'])]
    public function index(SneakerRepository $sneakerRepository): Response
    {
        return $this->render('back/category/index.html.twig', [
            'sneakers' => $sneakerRepository->findAll()
        ]);
    }

    #[Route('/{id}', name: 'category_show', methods: ['GET'])]
    public function show(Category $category, SneakerRepository $sneakerRepository): Response
    {
        return $this->render('front/category/show.html.twig', [
            'category' => $category,
            'sneakers' => $sneakerRepository->findBy([ 'category' => $category->getId()])
        ]);
    }
}
