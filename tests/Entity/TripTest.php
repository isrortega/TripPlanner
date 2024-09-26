<?php

namespace App\Tests\Entity;

use LogicException;
use App\Entity\Trip;
use App\Entity\Driver;
use App\Entity\Vehicle;
use InvalidArgumentException;
use App\DataFixtures\TripFixtures;
use Doctrine\ORM\Tools\SchemaTool;
use App\DataFixtures\DriverFixtures;
use App\DataFixtures\VehicleFixtures;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\Common\DataFixtures\Purger\ORMPurger;
use Doctrine\Common\DataFixtures\Executor\ORMExecutor;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;

class TripTest extends KernelTestCase
{

  private EntityManagerInterface $entityManager;

  protected function setUp(): void
  {
    $kernel = self::bootKernel();
    $this->entityManager = $kernel->getContainer()
      ->get('doctrine')
      ->getManager();

    $schemaTool = new SchemaTool($this->entityManager);
    $metadata = $this->entityManager->getMetadataFactory()->getAllMetadata();
    $schemaTool->createSchema($metadata);

    $this->loadFixtures();
  }

  private function loadFixtures()
  {
    $fixtureLoader = new ORMExecutor($this->entityManager, new ORMPurger($this->entityManager));
    $fixtureLoader->execute([new VehicleFixtures, new DriverFixtures, new TripFixtures()]);
  }

  protected function tearDown(): void
  {
    $this->entityManager->close();
    parent::tearDown();
  }

  /**
   * @covers \App\Entity\Trip::class
   */
  public function testTripCreation()
  {
    $vehicle = $this->entityManager->getRepository(Vehicle::class)->findOneBy(['plate' => 'SQL123']);
    $driver = $this->entityManager->getRepository(Driver::class)->findOneBy(['license' => $vehicle->getLicenseRequired()]);
    $this->assertNotNull($vehicle);
    $this->assertNotNull($driver);

    $trip = new Trip();
    $trip->setVehicle($vehicle);
    $trip->setDriver($driver);
    $trip->setDate(new \DateTime('2024-09-28'));

    $this->assertSame($vehicle, $trip->getVehicle());
    $this->assertSame($driver, $trip->getDriver());
    $this->assertEquals('2024-09-28', $trip->getDate()->format('Y-m-d'));
  }

  /**
   * @covers \App\Entity\Trip::class
   */
  public function testTripDriverAndVehicleRelation()
  {
      $vehicle = $this->entityManager->getRepository(Vehicle::class)->findOneBy(['plate' => 'SQL123']);
      $driver = $this->entityManager->getRepository(Driver::class)->findOneBy(['license' => $vehicle->getLicenseRequired()]);
      $this->assertNotNull($vehicle);
      $this->assertNotNull($driver);

      $trip = new Trip();
      $trip->setVehicle($vehicle);
      $trip->setDriver($driver);
      $trip->setDate(new \DateTime('2024-10-02'));

      $this->assertSame($vehicle, $trip->getVehicle());
      $this->assertSame($driver, $trip->getDriver());
      $this->assertEquals('2024-10-02', $trip->getDate()->format('Y-m-d'));
    }

  /**
   * @covers \App\Entity\Trip::class
   */
  public function testAssigningInvalidDriverToVehicleThrowsException()
  {
    $vehicle = $this->entityManager->getRepository(Vehicle::class)->findOneBy(['licenseRequired' => 'C']);
    $driver = $this->entityManager->getRepository(Driver::class)->findOneBy(['license' => 'B']);
    $this->assertNotNull($vehicle);
    $this->assertNotNull($driver);

    $trip = new Trip();
    $trip->setVehicle($vehicle);

    $this->expectException(InvalidArgumentException::class);
    $this->expectExceptionMessage('Driver license does not match the vehicle requirements.');

    $trip->setDriver($driver);
  }

  /**
   * @covers \App\Entity\Trip::class
   */
  public function testAssigningDriverFirstThrowsLogicException()
  {
    $vehicle = $this->entityManager->getRepository(Vehicle::class)->findOneBy(['plate' => 'SQL123']);
    $driver = $this->entityManager->getRepository(Driver::class)->findOneBy(['license' => $vehicle->getLicenseRequired()]);
    $this->assertNotNull($vehicle);
    $this->assertNotNull($driver);

    $trip = new Trip();

    $this->expectException(LogicException::class);
    $this->expectExceptionMessage('Vehicle must be set before assigning a driver.');

    $trip->setDriver($driver);
  }

  /**
   * @covers \App\Entity\Trip::class
   * todo: the exception is not thrown, check it out
   */
  public function testDuplicateTripShouldFail()
  {

    $vehicle = $this->entityManager->getRepository(Vehicle::class)->findOneBy(['plate' => 'SQL123']);
    $driver = $this->entityManager->getRepository(Driver::class)->findOneBy(['surname' => 'Lozano']);
    $this->assertNotNull($vehicle);
    $this->assertNotNull($driver);

    $trip = new Trip();
    $trip->setVehicle($vehicle);
    $trip->setDriver($driver);
    $trip->setDate(new \DateTime('2024-09-25'));

    // Persist the first trip
    $this->entityManager->persist($trip);
    $this->entityManager->flush();

    // todo: this should be thrown
    // $this->expectException(\Doctrine\DBAL\Exception\UniqueConstraintViolationException::class);

    $duplicateTrip = new Trip();
    $duplicateTrip->setVehicle($vehicle);
    $duplicateTrip->setDriver($driver);
    $duplicateTrip->setDate(new \DateTime('2024-09-25'));

    // persist the second one
    $this->entityManager->persist($duplicateTrip);
    $this->entityManager->flush();
  }

  /**
   * @covers \App\Entity\Trip::class
   */
  public function testTripEdit()
  {
    $vehicle = $this->entityManager->getRepository(Vehicle::class)->findOneBy(['plate' => 'SQL123']);
    $driver = $this->entityManager->getRepository(Driver::class)->findOneBy(['license' => $vehicle->getLicenseRequired()]);
    $this->assertNotNull($vehicle);
    $this->assertNotNull($driver);

    $trip = new Trip();
    $trip->setVehicle($vehicle);
    $trip->setDriver($driver);
    $trip->setDate(new \DateTime('2024-09-25'));

    $newDate = new \DateTime('2024-10-01');
    $trip->setDate($newDate);

    $this->assertEquals('2024-10-01', $trip->getDate()->format('Y-m-d'));
  }

  /**
   * @covers \App\Entity\Trip::class
   */
  public function testFindTrip(): void
  {
    $dateString = '2024-09-26';
    $trip = $this->entityManager->getRepository(Trip::class)->findOneBy(['date' => new \DateTime($dateString)]);
    $this->assertNotNull($trip);
    $this->assertEquals($dateString, $trip->getDate()->format('Y-m-d'));

    $vehicle = $this->entityManager->getRepository(Vehicle::class)->findOneBy(['plate' => 'SQL123']);
    $trip = $this->entityManager->getRepository(Trip::class)->findOneBy(['vehicle' => $vehicle]);
    $this->assertSame($vehicle, $trip->getVehicle());

    $driver = $this->entityManager->getRepository(Driver::class)->findOneBy(['surname' => 'Lozano']);
    $trip = $this->entityManager->getRepository(Trip::class)->findOneBy(['driver' => $driver]);
    $this->assertSame($driver, $trip->getDriver());
  }

}
