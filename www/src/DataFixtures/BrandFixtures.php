<?php

namespace App\DataFixtures;

use App\Entity\Brand;
use Doctrine\Persistence\ObjectManager;
use Doctrine\Bundle\FixturesBundle\Fixture;

class BrandFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        $faker = \Faker\Factory::create();
        for ($i = 0; $i < 100; $i++) {
            $object = (new Brand())
                ->setName($faker->word)
                ->setDescription($faker->realText(200))
            ;

            $manager->persist($object);
        }
        $manager->flush();
    }
}
