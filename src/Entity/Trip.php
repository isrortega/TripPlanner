<?php

namespace App\Entity;

use LogicException;
use InvalidArgumentException;
use Doctrine\ORM\Mapping as ORM;
use App\Repository\TripRepository;

#[ORM\Entity(repositoryClass: TripRepository::class)]
#[ORM\Table(name: 'trip', uniqueConstraints: [
    new ORM\UniqueConstraint(name: 'trip_unique', columns: ['vehicle_id', 'driver_id', 'date'])
])]
class Trip
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\ManyToOne(targetEntity: Vehicle::class)]
    #[ORM\JoinColumn(name: 'vehicle_id', referencedColumnName: 'id', nullable: false)]
    private ?Vehicle $vehicle = null;

    #[ORM\ManyToOne(targetEntity: Driver::class)]
    #[ORM\JoinColumn(name: 'driver_id', referencedColumnName: 'id', nullable: false)]
    private ?Driver $driver = null;

    #[ORM\Column(type: 'date')]
    private ?\DateTimeInterface $date = null;


    /**
     * Get the value of vehicle
     */
    public function getVehicle(): ?Vehicle
    {
        return $this->vehicle;
    }

    /**
     * Set the value of vehicle
     */
    public function setVehicle(?Vehicle $vehicle): self
    {
        $this->vehicle = $vehicle;

        return $this;
    }

    /**
     * Get the value of driver
     */
    public function getDriver(): ?Driver
    {
        return $this->driver;
    }

    /**
     * Set the value of driver
     */
    public function setDriver(?Driver $driver): self
    {
        if ($this->vehicle === null) {
           throw new LogicException('Vehicle must be set before assigning a driver.');
        }

        if ($this->vehicle->getLicenseRequired() !== $driver->getLicense()) {
            throw new InvalidArgumentException('Driver license does not match the vehicle requirements.');
        }

        $this->driver = $driver;

        return $this;
    }

    /**
     * Get the value of date
     */
    public function getDate(): ?\DateTimeInterface
    {
        return $this->date;
    }

    /**
     * Set the value of date
     */
    public function setDate(?\DateTimeInterface $date): self
    {
        $this->date = $date;

        return $this;
    }
    
    public function getStringdate(): string
    {
        return $this->date ? $this->date->format('m/d/Y') : '';
    }

}
