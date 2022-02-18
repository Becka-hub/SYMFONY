<?php

namespace App\DataFixtures;

use App\Entity\Car;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class AppFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        for($i=0;$i < 10;$i++){
            $car = new Car();
            $car->setName("voiture".$i);
            $car->setMark("vo".$i);
            $car->setNumber("T".$i."voiture".$i);
            $manager->persist($car);
         }

        $manager->flush();
    }
}
