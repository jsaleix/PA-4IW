<?php

namespace App\Controller\Back;

use App\Entity\Category;
use App\Repository\SneakerRepository;
use App\Repository\CategoryRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/admin/categories')]
class CategoryController extends AbstractController
{
    #[Route('/', name: 'category_index', methods: ['GET'])]
    public function index(CategoryRepository $categoryRepository): Response
    {
        return $this->render('back/category/index.html.twig', [
            'categories' => $categoryRepository->findAll()
        ]);
    }

    #[Route('/{id}', name: 'category_show', methods: ['GET'])]
    public function show(Category $category, SneakerRepository $sneakerRepository): Response
    {
        return $this->render('back/category/show.html.twig', [
            'category' => $category,
            'sneakers' => $sneakerRepository->findBy([ 'category' => $category->getId()])
        ]);
    }
}
