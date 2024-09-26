<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use App\Repository\VehicleRepository;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;

#[ORM\Entity(repositoryClass: VehicleRepository::class)]
#[UniqueEntity(fields: ['plate'], message: 'This plate number is already in use.')]
class Vehicle
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 50)]
    private ?string $brand = null;

    #[ORM\Column(length: 50)]
    private ?string $model = null;

    #[ORM\Column(length: 15)]
    private ?string $plate = null;

    #[ORM\Column(length: 1)]
    private ?string $licenseRequired = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    /**
     * Get the value of brand
     */
    public function getBrand(): ?string
    {
        return $this->brand;
    }

    /**
     * Set the value of brand
     */
    public function setBrand(?string $brand): self
    {
        $this->brand = $brand;

        return $this;
    }

    /**
     * Get the value of model
     */
    public function getModel(): ?string
    {
        return $this->model;
    }

    /**
     * Set the value of model
     */
    public function setModel(?string $model): self
    {
        $this->model = $model;

        return $this;
    }

    /**
     * Get the value of plate
     */
    public function getPlate(): ?string
    {
        return $this->plate;
    }

    /**
     * Set the value of plate
     */
    public function setPlate(?string $plate): self
    {
        $this->plate = $plate;

        return $this;
    }

    /**
     * Get the value of licenseRequired
     */
    public function getLicenseRequired(): ?string
    {
        return $this->licenseRequired;
    }

    /**
     * Set the value of licenseRequired
     */
    public function setLicenseRequired(?string $licenseRequired): self
    {
        $this->licenseRequired = $licenseRequired;

        return $this;
    }

    /**
     * get the full vehicle info to use it on select inputs
     */
    public function getFullInfo(): string
    {
        return sprintf('%s - %s, %s [%s]', $this->plate, $this->brand, $this->model, $this->licenseRequired);
    }
}
