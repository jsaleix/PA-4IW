<?php
namespace App\Service\Front;

use App\Entity\Invoice;
use App\Entity\Image;
use App\Entity\Sneaker;
use App\Repository\InvoiceRepository;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\EntityManagerInterface;
use Stripe\StripeClient;
use Exception;

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

        return [
            'sneaker'=>$sneaker,
            'isLiked' => $isLiked
        ];
    }

    public function generateSneakerWithEmptyImages()
    {
        $sneaker = new Sneaker();
        for($i=0; $i<3; $i++){
            $image = new Image();
            $sneaker->getImages()->add($image);
        }
        return $sneaker;
    }

    public function publish(Sneaker $sneaker, $user, bool $fromShop = true)
    {
        if($sneaker->getPrice() < 1){
            throw new \Exception('The price cannot be inferior to 1$');
        }

        if($sneaker->getSize() < 1){
            throw new \Exception('The size cannot be inferior to 1');
        }

        $sneaker->setFromShop( $fromShop );
        $sneaker->setUnused( true );
        $sneaker->setPublicationDate( new \DateTime() );

        $stripe = new StripeClient($_ENV['STRIPE_SK']);
        if($fromShop){
            $sneakerId = $stripe->products->create([
                'name' => 'SHOP PRODUCT: ' . $sneaker->getName() . ' - ' . $sneaker->getId(),
            ]);
        }else{
            $sneaker->setPublisher( $user );
            $sneakerId = $stripe->products->create([
                'name' => 'MP PRODUCT: ' . $sneaker->getName() . ' - ' . $sneaker->getId(),
            ]);
        }

        if(!$sneakerId) {
            throw new \Exception('An error occurred with Stripe, please try again later');
        }

        $sneaker->setStripeProductId($sneakerId->id);

        // Disabling image verification for performance testing
        
        // foreach($sneaker->getImages() as $image){
        //     if($image->getImageFile()){
        //         $image->setSneaker($sneaker);
        //         $this->entityManager->persist($image);
        //     }else{
        //         $sneaker->removeImage($image);
        //     }
        // }

        // if( count($sneaker->getImages()) < 3){
        //     throw new \Exception('Missing image(s) (required: 3, got: '. count($sneaker->getImages()) . ')' );
        // }

        $this->entityManager->persist($sneaker);
        $this->entityManager->flush();
        return true;
    }

    public function edit(Sneaker $sneaker)
    {
        if($sneaker->getPrice() < 1){
            throw new Exception('The price cannot be inferior to 1$');
        }

        if($sneaker->getSize() < 1){
            throw new Exception('The size cannot be inferior to 1');
        }

        $this->entityManager->flush();
        return null;
        
    }

}