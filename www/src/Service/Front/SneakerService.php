<?php
namespace App\Service\Front;

use App\Entity\Invoice;
use App\Entity\Sneaker;
use App\Repository\InvoiceRepository;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\EntityManagerInterface;

class SneakerService
{
    public function __construct(
        private InvoiceRepository $invoiceRepository,
        private EntityManagerInterface $entityManager
    )
    {}

    public function renderPage(Sneaker $sneaker, $user){
        $isLiked = false;

        if( $user && $user->getFavoris()->contains($sneaker)
        ){
            $isLiked = true;
        }

        $invoice = $this->invoiceRepository->findOneBy(['sneaker' => $sneaker]);
        if($invoice && $invoice->getPaymentStatus() === Invoice::SOLD_STATUS ){
            $sold = true;
        }else{
            $sold = false;
        }

        return [
            'sneaker'=>$sneaker,
            'sold' => $sold,
            'isLiked' => $isLiked
        ];
    }

}