<?php

namespace App\Controller\Back;

use App\Entity\Sneaker;
use App\Form\Back\SneakerType;
use App\Repository\SneakerRepository;
use App\Service\Front\SneakerService;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/admin/shop')]
class AdminShopController extends AbstractController
{
    #[Route('/', name: 'admin_shop_index')]
    public function index(SneakerRepository $sneakerRepository, Request $request): Response
    {
        $params = ['from_shop' => true];
        $filters = ['id' => 'DESC'];

        $statusParam    = $request->query->get('status');
        $orderParam     = $request->query->get('order');

        if( $orderParam && in_array($orderParam, ['ASC', 'DESC'])){
            $filter['id'] = $orderParam;
        }

        if( $statusParam && in_array($statusParam, ['sold', 'buyable']) ){
            $params['sold'] = $statusParam === 'sold' ? true : null;
        }

        $sneakers = $sneakerRepository->findBy( $params, $filters);
        return $this->render('back/shop/index.html.twig', [
            'sneakers'=>$sneakers
        ]);
    }

    #[Route('/publish', name: 'admin_shop_publish')]
    public function publish(Request $request, SneakerService $sneakerService, SneakerRepository $sneakerRepository): Response
    {
        $user = $this->getUser();
        if( !in_array('ROLE_ADMIN',  $user->getRoles()) ){
            return $this->redirectToRoute('front_account_index', [], Response::HTTP_SEE_OTHER);
        }

        $sneaker = $sneakerService->generateSneakerWithEmptyImages();
        $form = $this->createForm(SneakerType::class, $sneaker);
        $form->handleRequest($request);

        if( $form->isSubmitted() && $form->isValid() ){
            try{
                $sneakerService->publish($sneaker, $user, true);
                return $this->redirectToRoute('admin_shop_index');
            }catch(Exception $e){
                $this->addFlash( 'warning', $e->getMessage()??'An error occurred' );
            }
        }
        return $this->render('back/shop/publish.html.twig', [
            'formSneaker' => $form->createView()
        ]);
    }

}
