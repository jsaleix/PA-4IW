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
use Symfony\Component\Finder\Finder;
use Symfony\Component\HttpFoundation\File\File;

use Stripe\StripeClient;

class PerformanceFixtures extends Fixture
{
    const USER_ADMIN = 'admin';
    const USER_SELLER = 'seller';
    const USER_USER = 'user';

    /** @var UserPasswordHasherInterface $userPasswordHasher */
    private $userPasswordHasher;

    public function __construct(
            UserPasswordHasherInterface $userPasswordHasher, 
            SneakerService $sneakerService,
            BrandRepository $brandRepository
    )
    {
        $this->userPasswordHasher = $userPasswordHasher;
        $this->sneakerService = $sneakerService;
        $this->brandRepository = $brandRepository;
    }

    public function load(ObjectManager $manager): void
    {


        //Fetching base user json
        $finder = new Finder();
        $finder->files()->in(__DIR__."//files//")->name('base_user.json');

        if (!$finder->hasResults()) {
            return;
        }

        foreach ($finder as $file) {
            $absoluteFilePath = $file->getRealPath();
            $fileNameWithExtension = $file->getRelativePathname();
            $baseUser = json_decode(file_get_contents($absoluteFilePath));
        }

        if( !$baseUser ) throw new \Exception('Base user not found');

        $brand = (new Brand())
                ->setName("Performance brand")
                ->setDescription("Performance brand")
                ;
        $manager->persist($brand);

        for($i = 0; $i < 50; $i++) {
            $email = $baseUser->emailStart . $i . $baseUser->emailEnd;
            $user = (new User())
                ->setEmail($email)
                ->setName($baseUser->lastname)
                ->setSurname($baseUser->firstname)
                ->setCity("Paris")
                ->setAddress("242 rue du faubourg st. Antoine")
                ->setIsVerified(true)
                ->setRoles(['ROLE_SELLER'])
            ;
            $user->setPassword($this->userPasswordHasher->hashPassword($user, $baseUser->password));

            //$sneaker = $this->sneakerService->generateSneakerWithEmptyImages();
            $sneaker = (new Sneaker())
                ->setName('MOCK PRODUCT')
                ->setSize(44)
                ->setPrice(100)
                ->setDescription("Mock product description")
                ->setPublisher($user)
                ->setBrand($brand)
            ;

            // for($i=0; $i<3; $i++){
            //     $image = new Image();
            //     $image->setPath('/images/mock_product.jpg');
            //     $image->setName('/images/mock_product.jpg');
            //     //$image->setImageFile(new File());
            //     $sneaker->addImage($image);
            // }

            //throw new \Exception( count($sneaker->getImages()[0]->getImageFile()) );
            //throw new \Exception( count($sneaker->getImages()) );
            
            $manager->persist($user);
            $this->setReference(self::USER_USER, $user);

            $this->sneakerService->publish($sneaker, $user, false);

        }

        $manager->flush();
    }
}
