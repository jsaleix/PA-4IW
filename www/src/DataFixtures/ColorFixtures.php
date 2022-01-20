<?php

namespace App\DataFixtures;

use App\Entity\Color;
use Doctrine\Persistence\ObjectManager;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Symfony\Component\Finder\Finder;

class ColorFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        $finder = new Finder();
        $finder->files()->in(__DIR__)->name('colors.json');

        if (!$finder->hasResults()) {
            return;
        }

        foreach ($finder as $file) {
            $absoluteFilePath = $file->getRealPath();
            $fileNameWithExtension = $file->getRelativePathname();
            $colorFamilies = json_decode(file_get_contents($absoluteFilePath));
        }

        foreach($colorFamilies as $colorItems){

            foreach($colorItems->children as $colorItem){
                $object = (new Color())
                    ->setName($colorItems->name . ' ' . $colorItem->name)
                    ->setHexaValue( $colorItem->hex)
                ;
                //dd($colorItem);
            }
            $manager->persist($object);
        };
        
        $manager->flush();
    }
}
