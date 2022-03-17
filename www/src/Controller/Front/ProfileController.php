<?php

namespace App\Controller\Front;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\User\UserInterface;

#[Route('/profile')]
class ProfileController extends AbstractController
{
    #[Route('/{id}', name: 'front_profile')]
    public function index(UserInterface $user): Response
    {
        return $this->render('@front/profile/index.html.twig', [
            'user' => $user,
        ]);
    }

    #[Route('/debug/{id}', name: 'front_profile_debug')]
    public function debug(UserInterface $user): Response
    {
        dd($user);
        return $this->render('@front/profile/index.html.twig', [
            'user' => $user,
        ]);
    }
}
