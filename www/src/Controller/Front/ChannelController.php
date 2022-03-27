<?php

namespace App\Controller\Front;

use App\Entity\Channel;
use App\Repository\MessageRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;


class ChannelController extends AbstractController
{
    #[Route('/channel', name: 'app_channel')]
    public function index(): Response
    {
        return $this->render('front/channel/index.html.twig', [
            'channel' => $channels ?? []
        ]);
    }

    #[Route('/chat/{id}', name: 'chat')]
    public function chat(Channel $channel, MessageRepository $messageRepository): Response
    {
        $messages = $messageRepository->findBy([
            'channel' => $channel],
            ['createdAt' => 'ASC']);

        return $this->render('front/channel/chat.html.twig', [
            'channel' => $channel,
            'messages' => $messages
        ]);
    }
}
