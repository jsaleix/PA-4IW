<?php

namespace App\Controller\Front;

use App\Entity\Image;
use App\Entity\Invoice;
use App\Entity\Sneaker;
use App\Form\Front\SneakerType;
use App\Repository\InvoiceRepository;
use App\Repository\SneakerRepository;
use App\Security\Voter\SneakerVoter;
use App\Service\Front\SneakerService;

use Doctrine\ORM\EntityManager;
use Doctrine\ORM\EntityManagerInterface;
use Exception;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class SneakersController extends AbstractController
{
    #[Route('/account/publish', name: 'front_account_sneaker_add')]
    public function addMP(Request $request, SneakerService $sneakerService)//Adding sneakers on the Marketplace
    {
        $user = $this->getUser();
        if( !in_array('ROLE_SELLER',  $user->getRoles()) ){
            return $this->redirectToRoute('front_account_index', [], Response::HTTP_SEE_OTHER);
        }

        $sneaker = $sneakerService->generateSneakerWithEmptyImages();
        $form = $this->createForm(SneakerType::class, $sneaker);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            try{
                $sneakerService->publish($sneaker, $user, false);
                return $this->redirectToRoute('default');
            }catch(Exception $e){
                $this->addFlash( 'warning', $e->getMessage()??'An error occurred' );
            }
        }

        return $this->render('front/account/sneakers/new.html.twig', [
            'formSneaker' => $form->createView()
        ]);
    }

    #[Route('/account/your-products', name: 'front_account_seller_products')]
    public function sellerList(SneakerRepository $sneakerRepository){//Displays the products a seller user owns/is selling
        $user = $this->getUser();

        if( !in_array('ROLE_SELLER',  $user->getRoles()) ){
            return $this->redirectToRoute('front_account_index', [], Response::HTTP_SEE_OTHER);
        }

        //$sales = $invoiceRepository->findBy(['paymentStatus'=> Invoice::SOLD_STATUS]);
        $sales = $sneakerRepository->findUserSneakersByInvoiceStatus(Invoice::SOLD_STATUS, $user);
        //dd( $sales);
        return $this->render('front/account/sneakers/list.html.twig', [
            'sneakers' => $user->getPublishedSneakers(),
            'sales' => $sales
        ]);
    }

    #[Route('/account/sneaker/{id}', name: 'front_account_sneaker_edit', methods: ['GET', 'POST'])]
    #[IsGranted(SneakerVoter::EDIT, 'sneaker')]
    public function editSneaker(Request $request, Sneaker $sneaker)//Edit sneakers as seller
    {
        $user = $this->getUser();
        if( !in_array('ROLE_SELLER',  $user->getRoles()) ){
            return $this->redirectToRoute('front_account_index', [], Response::HTTP_SEE_OTHER);
        }

        $form = $this->createForm(SneakerType::class, $sneaker);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('front_account_seller_products', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('front/account/sneakers/edit.html.twig', [
            'sneaker'=>$sneaker,
            'form' => $form->createView()
         ]);
    }

    #[Route('/account/sneaker/delete/{id}', name: 'front_sneaker_delete', requirements: ['id' => '^\d+$'], methods: ['POST'])]
    #[IsGranted(SneakerVoter::DELETE, 'sneaker')]
    public function delete(Sneaker $sneaker, Request $request, EntityManagerInterface $entityManager, InvoiceRepository $invoiceRepository): Response
    {
        if ($this->isCsrfTokenValid('delete'.$sneaker->getId(), $request->request->get('_token'))) {
            $invoice = $invoiceRepository->findOneBy(['sneaker' => $sneaker]);
            if($invoice){
                $entityManager->remove($invoice);
            }
            foreach($sneaker->getImages() as $img){
                $sneaker->removeImage($img);
                //$entityManager->remove($img);
            }
            $entityManager->remove($sneaker);
            $entityManager->flush();
        }else{
            $this->addFlash('warning', "An error occurred.");
        }

        return $this->redirectToRoute('front_account_seller_products', [], Response::HTTP_SEE_OTHER);

        /*if ($this->isCsrfTokenValid('delete_sneaker', $token)) {
            $em = $this->getDoctrine()->getManager();
            $em->remove($sneaker);
            $em->flush();

            $this->addFlash('success', "Sneaker {$sneaker->getName()} has been successfuly removed.");

            return $this->redirectToRoute('front_account_seller_sales');
        }

        throw new Exception('Invalid token');*/
    }

    #[Route('/sneaker/like/{id}', name: 'front_like_sneaker_item', requirements: ['id' => '^\d+$'], methods: ['POST'])]
    public function likeSneaker( Sneaker $sneaker, EntityManagerInterface $entityManager, Request $request)
    {
        if( !$this->getUser() ){
            return $this->redirectToRoute('front_sneaker_item_by_slug', ['slug' => $sneaker->getSlug()]);
        }
        if ($this->isCsrfTokenValid('like'.$sneaker->getId(), $request->request->get('_token'))) {
            $user = $this->getUser();
            if($user->getFavoris()->contains($sneaker)){
                $user->removeFavori($sneaker);
            }else{
                $user->addFavori($sneaker);
            }
            $entityManager->flush();

        }else{
            $this->addFlash('warning', "An error occurred.");
        }

        return $this->redirectToRoute('front_sneaker_item_by_slug', ['slug' => $sneaker->getSlug()]);
    }

    #[Route('/sneaker/{id}', name: 'front_sneaker_item', methods: ['GET'])]
    public function sneakerPage( Sneaker $sneaker, SneakerService $sneakerService)
    {
        $user = $this->getUser();
        $vars = $sneakerService->renderPage($sneaker, $user);
        if($sneaker->getFromShop()){
            return $this->render('front/sneaker/sneaker.shop.html.twig', $vars);
        }else{
            return $this->render('front/sneaker/sneaker.mp.html.twig', $vars);
        }
    }

    #[Route('/sneaker/t/{slug}', name: 'front_sneaker_item_by_slug', methods: ['GET'])]
    public function sneakerPageSlug( Sneaker $sneaker, SneakerService $sneakerService)
    {
        $user = $this->getUser();
        $vars = $sneakerService->renderPage($sneaker, $user);
        if($sneaker->getFromShop()){
            return $this->render('front/sneaker/sneaker.shop.html.twig', $vars);
        }else{
            return $this->render('front/sneaker/sneaker.mp.html.twig', $vars);
        }
    }

}
