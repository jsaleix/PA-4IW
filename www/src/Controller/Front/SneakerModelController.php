<?php

namespace App\Controller\Front;

use App\Entity\SneakerModel;
use App\Form\Back\SneakerModelType;
use App\Repository\SneakerModelRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/models')]
class SneakerModelController extends AbstractController
{
    #[Route('/', name: 'app_sneaker_model_index', methods: ['GET'])]
    public function index(SneakerModelRepository $sneakerModelRepository): Response
    {
        return $this->render('front/models/index.html.twig', [
            'sneaker_models' => $sneakerModelRepository->findAll(),
        ]);
    }

    #[Route('/{id}', name: 'app_sneaker_model_show', methods: ['GET'])]
    public function show(SneakerModel $sneakerModel): Response
    {
        return $this->render('front/models/show.html.twig', [
            'sneaker_model' => $sneakerModel,
        ]);
    }

}
