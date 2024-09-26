<?php

namespace App\Tests\Entity;

use App\Entity\Driver;
use App\DataFixtures\DriverFixtures;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\Common\DataFixtures\Purger\ORMPurger;
use Doctrine\Common\DataFixtures\Executor\ORMExecutor;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;

class DriverTest extends KernelTestCase
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
    $fixtureLoader->execute([new DriverFixtures()]);
  }
  protected function tearDown(): void
  {
    $this->entityManager->close();
    parent::tearDown();
  }

  /**
   * @covers \App\Entity\Driver::class
   */
  public function testDriverCreation(): void
  {
    $driver = new Driver();
    $driver->setName('Jaime');
    $driver->setSurname('Bermudez');
    $driver->setLicense('A');

    $this->assertEquals('Jaime', $driver->getName());
    $this->assertEquals('Bermudez', $driver->getSurname());
    $this->assertEquals('A', $driver->getLicense());
  }

  /**
   * @covers \App\Entity\Driver::class
   */
  public function testEditDriver(): void
    {
      $driver = new Driver();
      $driver->setName('Fabiola');
      $driver->setSurname('Gonzalez');
      $driver->setLicense('C');

      $this->entityManager->persist($driver);
      $this->entityManager->flush();

      $driver->setName('Esther');
      $this->entityManager->flush();

      $this->assertEquals('Esther', $driver->getName());
    }

  /**
   * @covers \App\Entity\Driver::class
   */
    public function testFindDriver(): void
    {
      $driver = $this->entityManager->getRepository(Driver::class)->findOneBy(['surname' => 'Lozano']);
      $this->assertNotNull($driver);
      $this->assertEquals('Juan', $driver->getName());
    }

  /**
   * @covers \App\Entity\Driver::class
   */
    public function testDeleteDriver(): void
    {
      $driver = $this->entityManager->getRepository(Driver::class)->findOneBy(['surname' => 'Lozano']);

      $this->assertNotNull($driver);
      $this->entityManager->remove($driver);
      $this->entityManager->flush();

      $deletedDriver = $this->entityManager->getRepository(Driver::class)->findOneBy(['surname' => 'Lozano']);
      $this->assertNull($deletedDriver);
    }

}