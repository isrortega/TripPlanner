<?php

namespace App\Repository;

use App\Entity\Trip;
use App\Entity\Driver;
use Doctrine\Persistence\ManagerRegistry;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;

/**
 * @extends ServiceEntityRepository<Driver>
 */
class DriverRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Driver::class);
    }

    public function findAvailableOnDateAndLicense(\DateTimeInterface $date, string $licenseRequired)
    {
        return $this->createQueryBuilder('d')
            ->leftJoin('App\Entity\Trip', 't', 'WITH', 't.driver = d.id AND t.date = :date')
            ->where('t.id IS NULL')
            ->andWhere('d.license = :license')
            ->setParameter('date', $date->format('Y-m-d'))
            ->setParameter('license', $licenseRequired)
            ->getQuery()
            ->getResult();
    }

    //    /**
    //     * @return Driver[] Returns an array of Driver objects
    //     */
    //    public function findByExampleField($value): array
    //    {
    //        return $this->createQueryBuilder('d')
    //            ->andWhere('d.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->orderBy('d.id', 'ASC')
    //            ->setMaxResults(10)
    //            ->getQuery()
    //            ->getResult()
    //        ;
    //    }

    //    public function findOneBySomeField($value): ?Driver
    //    {
    //        return $this->createQueryBuilder('d')
    //            ->andWhere('d.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->getQuery()
    //            ->getOneOrNullResult()
    //        ;
    //    }
}
