<?php

namespace App\Tests\Entity;

use App\Entity\Vehicle;
use App\DataFixtures\VehicleFixtures;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\Common\DataFixtures\Purger\ORMPurger;
use Doctrine\Common\DataFixtures\Executor\ORMExecutor;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;

class VehicleTest extends KernelTestCase
{

  private EntityManagerInterface $entityManager;

  protected function setUp(): void
  {
    $kernel = self::bootKernel();
    $this->entityManager = $kernel->getContainer()
      ->get('doctrine')
      ->getManager();

    $schemaTool = new \Doctrine\ORM\Tools\SchemaTool($this->entityManager);
    $metadata = $this->entityManager->getMetadataFactory()->getAllMetadata();
    $schemaTool->createSchema($metadata);

    $this->loadFixtures();
  }

  private function loadFixtures()
  {
    $fixtureLoader = new ORMExecutor($this->entityManager, new ORMPurger($this->entityManager));
    $fixtureLoader->execute([new VehicleFixtures()]);
  }

  protected function tearDown(): void
  {
    $this->entityManager->close();
    parent::tearDown();
  }

  /**
   * @covers \App\Entity\Vehicle::class
   */
  public function testVehicleCreation(): void
  {
    $vehicle = new Vehicle();
    $vehicle->setBrand('Toyota');
    $vehicle->setModel('Corolla');
    $vehicle->setPlate('ABC123');
    $vehicle->setLicenseRequired('B');

    $this->assertEquals('Toyota', $vehicle->getBrand());
    $this->assertEquals('Corolla', $vehicle->getModel());
    $this->assertEquals('ABC123', $vehicle->getPlate());
    $this->assertEquals('B', $vehicle->getLicenseRequired());
  }

  /**
   * @covers \App\Entity\Vehicle::class
   */
  public function testEditVehicle(): void
  {
    $vehicle = new Vehicle();
    $vehicle->setBrand('Honda');
    $vehicle->setModel('Civic');
    $vehicle->setPlate('XYZ789');
    $vehicle->setLicenseRequired('B');

    $this->entityManager->persist($vehicle);
    $this->entityManager->flush();

    $vehicle->setModel('Accord');
    $this->entityManager->flush();

    $this->assertEquals('Accord', $vehicle->getModel());
  }

  /**
   * @covers \App\Entity\Vehicle::class
   */
  public function testFindVehicle(): void
  {
    $vehicle = $this->entityManager->getRepository(Vehicle::class)->findOneBy(['plate' => 'SQL123']);
    $this->assertNotNull($vehicle);
    $this->assertEquals('Kia', $vehicle->getBrand());
  }

  /**
   * @covers \App\Entity\Vehicle::class
   */
  public function testDeleteVehicle(): void
  {
    $vehicle = $this->entityManager->getRepository(Vehicle::class)->findOneBy(['plate' => 'SQL123']);

    $this->assertNotNull($vehicle);
    $this->entityManager->remove($vehicle);
    $this->entityManager->flush();

    $deletedVehicle = $this->entityManager->getRepository(Vehicle::class)->findOneBy(['plate' => 'SQL123']);
    $this->assertNull($deletedVehicle);
  }
}
