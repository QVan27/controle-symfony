<?php

namespace App\DataFixtures;

use Faker;
use App\Entity\Media;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class AppFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        // https://github.com/symfony/symfony/discussions/45639 solution

        $faker = Faker\Factory::create('fr_FR');

        for ($i = 0; $i < 10; $i++) {
            $media = new Media();
            $media->setNom($faker->name);
            $media->setSynopsis($faker->text);
            $media->setType($faker->randomElement(['film', 'série']));
            $media->setDate($faker->dateTime);
            $manager->persist($media);
        }

        $manager->flush();
    }
}