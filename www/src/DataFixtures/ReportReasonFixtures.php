<?php

namespace App\DataFixtures;

use App\Entity\ReportReason;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Symfony\Component\Finder\Finder;
use Symfony\Component\Console\Output\ConsoleOutput;

class ReportReasonFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        $output = new ConsoleOutput();
    
        $finder = new Finder();
        $finder->files()->in(__DIR__."//files//")->name('reasons.json');

        if (!$finder->hasResults()) {
            $output->writeln('Nope');
            return;
        }

        foreach ($finder as $file) {
            $absoluteFilePath = $file->getRealPath();
            $fileNameWithExtension = $file->getRelativePathname();
            $reasons = json_decode(file_get_contents($absoluteFilePath));
        }
        
        $faker = \Faker\Factory::create();
        foreach($reasons as $reason){
            $object = (new ReportReason())
                    ->setName($reason->name)
                    ->setType($reason->type)
                    ->setDescription($reason->description)
                    ;
            $manager->persist($object);
        };
        
        $manager->flush();
    }
}
