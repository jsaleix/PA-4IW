<?php

namespace App\Controller\Front;

use App\Entity\Channel;
use App\Repository\MessageRepository;
use phpDocumentor\Reflection\DocBlock\Tags\Link;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
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
    public function chat(Request $request, Channel $channel, MessageRepository $messageRepository): Response
    {
        $messages = $messageRepository->findBy([
            'channel' => $channel],
            ['createdAt' => 'ASC']);

        $hubUrl = $this->getParameter('mercure.default_hub');
        $this->addLink($request, new \Symfony\Component\WebLink\Link('mercure', $hubUrl));

        return $this->render('front/channel/chat.html.twig', [
            'channel' => $channel,
            'messages' => $messages
        ]);
    }
}
