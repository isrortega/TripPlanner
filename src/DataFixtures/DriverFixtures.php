<?php

namespace App\DataFixtures;

use App\Entity\Driver;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class DriverFixtures extends Fixture
{
    public function load(ObjectManager $manager)
    {
        $driver1 = new Driver();
        $driver1->setName('Juan');
        $driver1->setSurname('Lozano');
        $driver1->setLicense('B');
        $manager->persist($driver1);

        $driver2 = new Driver();
        $driver2->setName('Ana');
        $driver2->setSurname('Rodríguez');
        $driver2->setLicense('C');
        $manager->persist($driver2);

        $driver3 = new Driver();
        $driver3->setName('Carlos');
        $driver3->setSurname('Méndez');
        $driver3->setLicense('B');
        $manager->persist($driver3);

        $manager->flush();
    }
}
