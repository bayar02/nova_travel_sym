<?php

namespace App\DataFixtures;

use App\Entity\Vol;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Faker\Factory;

class AppFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        $faker = Factory::create();
        $airlines = ['Emirates', 'Qatar Airways', 'Turkish Airlines', 'Air France', 'Lufthansa'];
        $airports = ['CDG', 'JFK', 'DXB', 'LHR', 'FRA', 'IST', 'AMS', 'MAD', 'FCO', 'MUC'];
        
        for ($i = 0; $i < 50; $i++) {
            $vol = new Vol();
            
            // Random dates within next 6 months
            $departDate = $faker->dateTimeBetween('now', '+6 months');
            $arrivalDate = clone $departDate;
            $arrivalDate->modify('+' . $faker->numberBetween(1, 12) . ' hours');
            
            $vol->setCompagnie($faker->randomElement($airlines));
            $vol->setAeroportDepart($faker->randomElement($airports));
            $vol->setAeroportArrivee($faker->randomElement($airports));
            $vol->setDateDepart($departDate);
            $vol->setDateArrivee($arrivalDate);
            $vol->setPrix($faker->randomFloat(2, 100, 2000));
            $vol->setDestination($faker->city);
            
            $manager->persist($vol);
        }

        $manager->flush();
    }
} 