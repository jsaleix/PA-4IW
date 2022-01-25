<?php

namespace App\Controller\Front;

use App\Entity\User;
use App\Entity\Brand;
use App\Repository\CategoryRepository;
use App\Repository\SneakerRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Form\Front\UserType;

#[Route('/categories')]
class CategoryController extends AbstractController
{
    #[Route('/', name: 'category_index', methods: ['GET'])]
    public function index(CategoryRepository $categoryRepository): Response
    {
        return $this->render('front/category/index.html.twig', [
            'categories' => $categoryRepository->findAll()
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
