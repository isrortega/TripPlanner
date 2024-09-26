<?php

namespace App\Repository;

use App\Entity\Trip;
use App\Entity\Driver;
use App\Entity\Vehicle;
use Doctrine\Persistence\ManagerRegistry;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;

/**
 * @extends ServiceEntityRepository<Trip>
 */
class TripRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Trip::class);
    }

    public function findLastTripsByDriver(Driver $driver, int $limit = 10)
    {
        return $this->createQueryBuilder('t')
            ->andWhere('t.driver = :driverId')
            ->setParameter('driverId', $driver->getId())
            ->orderBy('t.date', 'DESC')
            ->setMaxResults($limit)
            ->getQuery()
            ->getResult();
    }

    public function findLastTripsByVehicle(Vehicle $vehicle, int $limit = 10)
    {
        return $this->createQueryBuilder('t')
            ->andWhere('t.vehicle = :vehicleId')
            ->setParameter('vehicleId', $vehicle->getId())
            ->orderBy('t.date', 'DESC')
            ->setMaxResults($limit)
            ->getQuery()
            ->getResult();
    }

}
