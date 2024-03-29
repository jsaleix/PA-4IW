<?php

namespace App\Controller\Front;

use App\Entity\Invoice;
use App\Entity\ProfileImage;

use App\Form\Front\UserPasswordType;
use App\Repository\InvoiceRepository;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Annotation\Route;

use App\Form\Front\Invoice\TrackingNumberFormType;
use App\Form\Front\UserMailType;
use App\Form\Front\UserType;
use App\Form\Front\UserDeleteSelfType;

use App\Security\Voter\InvoiceVoter;

use App\Service\Front\SellerService;
use App\Service\Front\UserService;

use Exception;
use Stripe\StripeClient;

#[Route('/account')]
class AccountController extends AbstractController
{
    #[Route('/', name: 'account_index', methods: ['GET'])]
    public function index(SellerService $sellerService): Response
    {
        $user = $this->getUser();
        $transfersCapabilities = false;
        if(in_array('ROLE_SELLER', $user->getRoles())){
            $transfersCapabilities = $sellerService->checkSellerCapabilities($user);
        }
        return $this->render('front/account/index.html.twig', [
            'transfersCapabilities' => $transfersCapabilities
        ]);
    }

    #[Route('/profile', name: 'account_profile', methods: ['GET', 'POST'])]
    public function profile(Request $request, EntityManagerInterface $entityManager): Response
    {
        $user = $this->getUser();
        if( !$user->getProfileImage() ){
            $user->setProfileImage(new ProfileImage());
        }
        $form = $this->createForm(UserType::class, $user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            try{
                $entityManager->flush();
                $this->addFlash('success', 'Success');
                $user->getProfileImage()->setImageFile(null);
                return $this->redirectToRoute('front_account_profile', [], Response::HTTP_SEE_OTHER);
            }catch(\Exception $e){
                //$this->addFlash('warning', $e->getMessage());
                $this->addFlash('warning', 'An error occured');
            }

        }

        //$user->setImageFile(null);
        return $this->render('front/account/profile/index.html.twig', [
            'user' => $user,
            'form' => $form->createView()
         ]);
    }

    #[Route('/profile/change-password', name: 'account_profile_password', methods: ['GET', 'POST'])]
    public function changePassword(Request $request, EntityManagerInterface $entityManager, UserPasswordHasherInterface $passwordHasher): Response
    {
        $form = $this->createForm(UserPasswordType::class);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $requestParams = $request->get('user_password');
            $oldPwd = $requestParams['oldPassword'];
            $newPwd = $requestParams['newPassword'];

            if($newPwd['first'] !== $newPwd['second']){
                $this->addFlash('warning', 'The passwords do not match');
            }

            $user = $this->getUser();
            if( $passwordHasher->isPasswordValid($user, $oldPwd) ){
                $user->setPassword($passwordHasher->hashPassword($user, $newPwd['first']));
                $entityManager->flush();
                return $this->redirectToRoute('front_account_profile', [], Response::HTTP_SEE_OTHER);
            }else{
                $this->addFlash('warning', 'Wrong password');
            }
        }
        return $this->render('front/account/profile/change_password.html.twig', [
            'form' => $form->createView()
        ]);
    }

    #[Route('/profile/change-mail', name: 'account_profile_mail', methods: ['GET', 'POST'])]
    public function changeMail(Request $request, EntityManagerInterface $entityManager, UserRepository $userRepository): Response
    {
        $user = $this->getUser();
        $form = $this->createForm(UserMailType::class, $user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $requestParams = $request->get('user_mail');
            $newMail = $requestParams['newMail'];

            $mailAlreadyTaken = $userRepository->findOneBy(['email' => $newMail]);
            if($mailAlreadyTaken){
                $this->addFlash('warning', 'This mail is already taken');
            }else{
                $user->setEmail($newMail);
                $entityManager->flush();
                return $this->redirectToRoute('front_account_profile', [], Response::HTTP_SEE_OTHER);
            }
        }
        return $this->render('front/account/profile/change_mail.html.twig', [
            'form' => $form->createView()
        ]);
    }

    #[Route('/account/profile/delete', name: 'account_profile_delete', methods: ['GET', 'POST'])]
    public function delete(
                            Request $request, 
                            EntityManagerInterface $entityManager,
                            UserService $userService,
                            UserPasswordHasherInterface $passwordHasher
    ): Response
    {
        $user = $this->getUser();
        $form = $this->createForm(UserDeleteSelfType::class, $user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            try{
                $requestParams = $request->get('user_delete_self');
                $pwd = $requestParams['password'];

                if( !$pwd ){
                    throw new Exception('Empty password field');
                }
                if( !$passwordHasher->isPasswordValid($user, $pwd) ){
                    throw new Exception('Wrong password');
                }
                if( $userService->hasActiveTransaction($user) ){
                    throw new Exception("You cannot delete your account since you have transaction(s) pending");
                }
                $img = $user->getProfileImage();
                if($img){
                    $entityManager->remove($img);
                }
                $entityManager->remove($user);
                $entityManager->flush();
                
                return $this->redirectToRoute('default', [], Response::HTTP_SEE_OTHER);
            }catch(Exception $e){
                $this->addFlash('danger', $e->getMessage());
            }
        }

        return $this->render('front/account/profile/delete_account.html.twig', [
            'form' => $form->createView()
        ]);
    }

    #[Route('/orders', name: 'account_orders', methods: ['GET'])]
    public function orders(Request $request): Response
    {
        $user = $this->getUser();
        return $this->render('front/account/orders/index.html.twig', [
            'invoices' => $user->getInvoices()
        ]);
    }

    #[Route('/orders/{id}', name: 'account_order', methods: ['GET'])]
    #[IsGranted(InvoiceVoter::VIEW, 'invoice')]
    public function order(Invoice $invoice): Response
    {
        return $this->render('front/account/orders/show.html.twig', [
            'invoice' => $invoice,
            'canReceiveParcel' => $invoice->getPaymentStatus() === Invoice::DELIVERING_STATUS
        ]);
    }


    #[Route('/orders/receive/{id}', name: 'account_receive_order', requirements: ['id' => '^\d+$'], methods: ['POST'])]
    #[IsGranted(InvoiceVoter::REPORT_AS_RECEIVED, 'invoice')]
    public function receiveParcel( Invoice $invoice, EntityManagerInterface $entityManager, Request $request)
    {
        if ($this->isCsrfTokenValid('receive'. $invoice->getId(), $request->request->get('_token'))) {
            if($invoice->getPaymentStatus() === Invoice::FINISHED_STATUS){
                return $this->redirectToRoute('account_order', ['id' => $invoice->getId()]);
            }
            $invoice->setPaymentStatus(Invoice::FINISHED_STATUS);
            $entityManager->flush();
        }else{
            $this->addFlash('warning', "An error occurred.");
        }

        return $this->redirectToRoute('account_order', ['id' => $invoice->getId()]);
    }


    #[Route('/become_seller', name: 'account_become_seller', methods: ['GET'])]
    public function becomeSeller(Request $request, SellerService $sellerService): Response
    {
        $user = $this->getUser();
        $url = $sellerService->promoteOrRedirect($user, $request);
        if(!$url){
            return $this->redirectToRoute('front_account_index', [], Response::HTTP_SEE_OTHER);
        }else{
            header('Location:' . $url);
        }
        return $this->render('front/account/becomeSeller/index.html.twig', []);
    }

    #[Route('/stripe-managing', name: 'account_manage_stripe', methods: ['GET'])]
    #[IsGranted("ROLE_SELLER")]
    public function manageStripeAccount(Request $request): Response
    {
        $user = $this->getUser();

        $stripe = new StripeClient($_ENV['STRIPE_SK']);
        if($user->getStripeConnectId()){
            $account = $user->getStripeConnectId();
        }else{
            return $this->redirectToRoute('front_account_index', [], Response::HTTP_SEE_OTHER);
        }

        $link = $stripe->accounts->createLoginLink(
            $user->getStripeConnectId()
        );

        header('Location:' . $link->url);
        return $this->render('front/account/becomeSeller/index.html.twig', []);
    }

    #[Route('/likes', name: 'account_likes', methods: ['GET'])]
    public function showLikes(Request $request): Response
    {
        $likes = $this->getUser()->getFavoris();
        return $this->render('front/account/likes/index.html.twig', [
            'likes' => $likes
        ]);
    }

    #[Route('/seller-orders', name: 'account_seller_orders', methods: ['GET'])]
    #[IsGranted("ROLE_SELLER")]
    public function sellerOrders(InvoiceRepository $invoiceRepository): Response
    {
        $user = $this->getUser();
        $waitingForTracking = $invoiceRepository->findUserInvoicesByStatus(Invoice::SOLD_STATUS, $user);
        $delivering         = $invoiceRepository->findUserInvoicesByStatus(Invoice::DELIVERING_STATUS, $user);
        $finished           = $invoiceRepository->findUserInvoicesByStatus(Invoice::FINISHED_STATUS, $user);

        return $this->render('front/account/seller-orders/index.html.twig', [
            'invoicesList' => [
                                'waiting for tracking' => $waitingForTracking,
                                'delivering' => $delivering,
                                'finished' => $finished
                            ]
        ]);
    }

    #[Route('/seller-orders/{id}', name: 'account_seller_order', methods: ['GET', 'POST'])]
    #[IsGranted("ROLE_SELLER")]
    #[IsGranted(InvoiceVoter::VIEW_AS_SELLER, 'invoice')]
    public function sellerOder(Invoice $invoice, Request $request, EntityManagerInterface $entityManager): Response
    {
        $canSetTrackingNb = $invoice->getPaymentStatus() === Invoice::SOLD_STATUS;
        if($canSetTrackingNb){
            $form = $this->createForm(TrackingNumberFormType::class, $invoice);
            $form->handleRequest($request);

            if( $form->isSubmitted() && $form->isValid() ) {
                $invoice->setPaymentStatus(Invoice::DELIVERING_STATUS);
                $entityManager->flush();
            }
            $canSetTrackingNb = $invoice->getPaymentStatus() === Invoice::SOLD_STATUS;
        }
        return $this->render('front/account/seller-orders/show.html.twig', [
            'invoice' => $invoice,
            'form' => $canSetTrackingNb ? $form->createView() : null
        ]);
    }

}
