<?php

namespace App\DataFixtures;

use App\Entity\Trip;
use App\Entity\Driver;
use App\Entity\Vehicle;
use Doctrine\Persistence\ObjectManager;
use Doctrine\Bundle\FixturesBundle\Fixture;

class TripFixtures extends Fixture
{

  public function load(ObjectManager $manager)
  {
    $vehicle1 = $manager->getRepository(Vehicle::class)->findOneBy(['plate' => 'SQL123']);
    $driver1 = $manager->getRepository(Driver::class)->findOneBy(['surname' => 'Lozano']);

    $trip1 = new Trip();
    $trip1->setVehicle($vehicle1);
    $trip1->setDriver($driver1);
    $trip1->setDate(new \DateTime('2024-09-25'));
    $manager->persist($trip1);

    $vehicle2 = $manager->getRepository(Vehicle::class)->findOneBy(['plate' => 'ABC789']);
    $driver2 = $manager->getRepository(Driver::class)->findOneBy(['surname' => 'Rodríguez']);

    $trip2 = new Trip();
    $trip2->setVehicle($vehicle2);
    $trip2->setDriver($driver2);
    $trip2->setDate(new \DateTime('2024-09-26'));
    $manager->persist($trip2);

    $manager->flush();
    }

  public function getDependencies()
  {
    return [
      VehicleFixtures::class,
      DriverFixtures::class,
    ];
  }
}
