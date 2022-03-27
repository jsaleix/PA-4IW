<?php

namespace App\Controller\Front;

use App\Entity\ProductReport;
use App\Entity\ReportReason;
use App\Entity\Sneaker;
use App\Entity\User;
use App\Entity\UserReport;
use App\Form\Front\ProductReportFormType;
use App\Form\Front\UserReportType;
use App\Repository\ReportReasonRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;

class ReportController extends AbstractController
{
    #[Route('/report/user/{id}', name: 'report-user')]
    public function reportUser(User $user, Request $request, EntityManagerInterface $entityManager, ReportReasonRepository $reasonRepository): Response
    {
        $reasons = $reasonRepository->findBy(['type' => '1']);
        $report = new UserReport();
        $form = $this->createForm( UserReportType::class, $report);
        $form->handleRequest($request);

        if( $form->isSubmitted() && $form->isValid() ){
            try{
                $report->setReported($user);
                $report->setReporter($this->getUser());
                $report->setStatus('pending');
                $entityManager->persist($report);
                $entityManager->flush();
            }catch(\Exception $e){

            }
        }
        return $this->render('front/report/report_user.html.twig', [
            'form' => $form->createView(),
            'reasons' => $reasons
        ]);
    }

    #[Route('/report/sneaker/{id}', name: 'report-sneaker')]
    public function reportSneaker(Sneaker $sneaker, Request $request, EntityManagerInterface $entityManager, ReportReasonRepository $reasonRepository): Response
    {
        $reasons = $reasonRepository->findBy(['type' => '2']);
        $report = new ProductReport();
        $form = $this->createForm( ProductReportFormType::class, $report);
        $form->handleRequest($request);

        if( $form->isSubmitted() && $form->isValid() ){
            try{
                $report->setProduct($sneaker);
                $report->setStatus('pending');
                $entityManager->persist($report);
                $entityManager->flush();
            }catch(\Exception $e){
                dd($e);
            }
        }
        return $this->render('front/report/report_sneaker.html.twig', [
            'form' => $form->createView(),
            'reasons' => $reasons
        ]);
    }
}
