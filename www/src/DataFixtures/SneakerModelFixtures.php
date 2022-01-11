<?php

namespace App\DataFixtures;

use App\Entity\Brand;
use App\Entity\SneakerModel;
use Doctrine\Persistence\ObjectManager;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Bundle\FixturesBundle\Fixture;

class SneakerModelFixtures extends Fixture implements DependentFixtureInterface
{
    public function load(ObjectManager $manager): void
    {
        $faker = \Faker\Factory::create();
        $brands = $manager->getRepository(Brand::class)->findAll();

        for ($i = 0; $i < 100; $i++) {
            $object = (new SneakerModel())
                ->setName($faker->word)
                ->setDescription($faker->realText(200))
                ->setBrand($faker->randomElement($brands))
            ;

            $manager->persist($object);
        }
        $manager->flush();
    }

    public function getDependencies()
    {
        return [
            BrandFixtures::class,
        ];
    }
}
