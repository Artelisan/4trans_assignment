<?php

namespace App\DataFixtures;

use App\Entity\City;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class CityFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        $cities = ['London', 'Prague', 'Sydney', 'Budapest'];

        foreach ($cities as $city) {
            $city = new City($city);
            $manager->persist($city);
        }

        $manager->flush();

    }
}
