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

class UserProducts extends Fixture
{
    const USER_ADMIN = 'admin';
    const USER_SELLER = 'seller';
    const USER_USER = 'user';

    /** @var UserPasswordHasherInterface $userPasswordHasher */
    private $userPasswordHasher;

    public function __construct(
            UserPasswordHasherInterface $userPasswordHasher, 
            SneakerService $sneakerService
    )
    {
        $this->userPasswordHasher = $userPasswordHasher;
        $this->sneakerService = $sneakerService;
    }

    public function load(ObjectManager $manager): void
    {
        $brand = $this->getReference(BrandFixtures::FIRST_BRAND);
        $faker = \Faker\Factory::create();

        for($i = 0; $i <15; $i++) {
            $user = (new User())
                ->setEmail($faker->email())
                ->setName($faker->lastName())
                ->setSurname($faker->name())
                ->setCity("Paris")
                ->setAddress("242 rue du faubourg st. Antoine")
                ->setIsVerified(true)
                ->setRoles(['ROLE_SELLER'])
            ;
            $user->setPassword($this->userPasswordHasher->hashPassword($user, "test"));


            $sneaker = ($this->createSneakerWithImages())
                ->setName('MOCK PRODUCT - DO NO BUY')
                ->setSize(44)
                ->setPrice(100)
                ->setDescription("Mock product description")
                ->setPublisher($user)
                ->setBrand($brand)
            ;

            $manager->persist($user);

            $this->sneakerService->publish($sneaker, $user, false);

        }

        $manager->flush();
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
        $src = __DIR__."/files/sneaker_default.jpeg";
        $newFile = __DIR__."/files/sneaker_default-". $index .".jpeg";
        
        copy($src, $newFile);

        $file = new UploadedFile(
            $newFile,
            'sneaker_default.jpeg',
            'image/jpeg',
            UPLOAD_ERR_OK,
            true //  Set test mode true !!! " Local files are used in test mode hence the code should not enforce HTTP uploads."
        );
        return $file;
    }
}