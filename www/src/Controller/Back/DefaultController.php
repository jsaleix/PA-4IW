<?php

namespace App\Controller\Back;

use App\Repository\CategoryRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class DefaultController extends AbstractController
{
    #[Route('/admin/dashboard', name: 'admin_dashboard_index')]
    public function index(CategoryRepository $categoryRepository): Response
    {
        return $this->render('back/default/index.html.twig', [
            'categories' => $categoryRepository->findAll()
        ]);
    }

}