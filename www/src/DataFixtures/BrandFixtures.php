<?php

namespace App\DataFixtures;

use App\Entity\Brand;
use Doctrine\Persistence\ObjectManager;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Symfony\Component\Finder\Finder;
use Symfony\Component\Console\Output\ConsoleOutput;

class BrandFixtures extends Fixture
{
    public const FIRST_BRAND = 'first-brand';

    public function load(ObjectManager $manager): void
    {
        $output = new ConsoleOutput();
    
        $finder = new Finder();
        $finder->files()->in(__DIR__."//files//")->name('brands.json');

        if (!$finder->hasResults()) {
            $output->writeln('Nope');
            return;
        }

        foreach ($finder as $file) {
            $absoluteFilePath = $file->getRealPath();
            $fileNameWithExtension = $file->getRelativePathname();
            $brands = json_decode(file_get_contents($absoluteFilePath));
        }
        
        $faker = \Faker\Factory::create();

        $first=true;
        foreach($brands as $brand){
            $object = (new Brand())
                    ->setName($brand->name)
                    ->setDescription($faker->realText(130))
                    ;
            $manager->persist($object);
            if($first){
                $this->addReference(self::FIRST_BRAND, $object);
                $first=false;
            }
        };
        
        $manager->flush();
    }
}
