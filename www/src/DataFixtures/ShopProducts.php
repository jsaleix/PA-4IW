<?php

namespace App\DataFixtures;

use App\Entity\User;
use App\Entity\Sneaker;
use App\Entity\Image;
use App\Entity\Brand;

use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\HttpFoundation\File\File;

use App\Repository\BrandRepository;
use App\Service\Front\SneakerService;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class ShopProducts extends Fixture
{
    public function __construct(SneakerService $sneakerService)
    {
        $this->sneakerService = $sneakerService;
    }

    public function load(ObjectManager $manager): void
    {
        $brand = $this->getReference(BrandFixtures::FIRST_BRAND);
        $faker = \Faker\Factory::create();

        for($i = 0; $i <5; $i++) {
            $sneaker = ($this->createSneakerWithImages())
                ->setName('MOCK PRODUCT - DO NO BUY')
                ->setSize(44)
                ->setPrice(120)
                ->setDescription("Mock product description")
                ->setBrand($brand)
                ->setFromShop(true)
            ;

            $this->sneakerService->publish($sneaker, null, true);
        }
    }

    private function createSneakerWithImages()
    {
        $sneaker = new Sneaker();
        for($i=0; $i<3; $i++){
            $image = new Image();
            $image->setImageFile($this->generateImg($i) );
            $sneaker->getImages()->add($image);
        }
        return $sneaker;
    }

    private function generateImg($index)
    {
        $src = __DIR__."/files/sneaker_shop_default.jpeg";
        $newFile = __DIR__."/files/sneaker_shop_default-". $index .".jpeg";
        
        copy($src, $newFile);

        $file = new UploadedFile(
            $newFile,
            'sneaker_shop_default.jpeg',
            'image/jpeg',
            UPLOAD_ERR_OK,
            true //  Set test mode true !!! " Local files are used in test mode hence the code should not enforce HTTP uploads."
        );
        return $file;
    }
}