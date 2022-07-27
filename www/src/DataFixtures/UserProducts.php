<?php

namespace App\DataFixtures;

use App\Entity\User;
use App\Entity\Sneaker;
use App\Entity\Image;
use App\Entity\Brand;

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
            $user->setPassword($this->userPasswordHasher->hashPassword($user, $faker->password()));

            // $sneaker = (new Sneaker())
            //     ->setName('MOCK PRODUCT - DO NO BUY')
            //     ->setSize(44)
            //     ->setPrice(100)
            //     ->setDescription("Mock product description")
            //     ->setPublisher($user)
            //     ->setBrand($brand)
            // ;

            $manager->persist($user);

            // $this->sneakerService->publish($sneaker, $user, false);

        }

        $manager->flush();
    }
}