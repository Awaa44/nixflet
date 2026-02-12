<?php

namespace App\DataFixtures;

use App\Entity\Serie;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Faker\Factory;

class SerieFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {

        $faker = Factory::create('fr_FR');

        for($i = 0; $i < 1000; $i++) {
            $serie = new Serie();
            $serie->setName($faker->realText(30))
                    ->setOverview($faker->realText(100))
                    ->setStatus($faker->randomElement(['returning', 'ended', 'Canceled']))
                    ->setVote($faker->randomFloat(2, 1,9.5))
                    ->setPopularity($faker->randomFloat(2, 50, 200))
                    ->setGenres($faker->randomElement(['Thriller', 'Drame', 'Comédie', 'Horreur', 'Sentimental', 'Western', 'SyFy']))
                    ->setFirstAirDate($faker->dateTimeBetween('-10 years', '-1 month'))
                    ->setDateCreated($faker->dateTimeBetween($serie->getFirstAirdate()))
            ;

            if($serie->getStatus() === 'returning') {
                $serie->setLastAirDate($faker->dateTimeBetween($serie->getFirstAirdate(), '-3 days'));
            }

            $manager->persist($serie);
        }

        $manager->flush();

    }
}
