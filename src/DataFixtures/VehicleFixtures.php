<?php

namespace App\DataFixtures;

use App\Entity\Vehicle;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class VehicleFixtures extends Fixture
{
  public function load(ObjectManager $manager)
  {
    $vehicle1 = new Vehicle();
    $vehicle1->setBrand('Kia');
    $vehicle1->setModel('Rio');
    $vehicle1->setPlate('SQL123');
    $vehicle1->setLicenseRequired('B');
    $manager->persist($vehicle1);

    $vehicle2 = new Vehicle();
    $vehicle2->setBrand('Toyota');
    $vehicle2->setModel('Corolla');
    $vehicle2->setPlate('ABC789');
    $vehicle2->setLicenseRequired('C');
    $manager->persist($vehicle2);

    $vehicle3 = new Vehicle();
    $vehicle3->setBrand('Chevrolet');
    $vehicle3->setModel('Aveo');
    $vehicle3->setPlate('XYZ456');
    $vehicle3->setLicenseRequired('B');
    $manager->persist($vehicle3);

    $manager->flush();
  }
}
