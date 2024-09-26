<?php

namespace App\Entity;

use App\Repository\DriverRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: DriverRepository::class)]
class Driver
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 50)]
    private ?string $name = null;

    #[ORM\Column(length: 50)]
    private ?string $surname = null;

    #[ORM\Column(length: 1)]
    private ?string $license = null;

    /**
     * Get the value of id
     */
    public function getId(): ?int
    {
        return $this->id;
    }

    /**
     * Get the value of name
     */
    public function getName(): ?string
    {
        return $this->name;
    }

    /**
     * Set the value of name
     */
    public function setName(?string $name): self
    {
        $this->name = $name;

        return $this;
    }

    /**
     * Get the value of surname
     */
    public function getSurname(): ?string
    {
        return $this->surname;
    }

    /**
     * Set the value of surname
     */
    public function setSurname(?string $surname): self
    {
        $this->surname = $surname;

        return $this;
    }

    /**
     * Get the value of license
     */
    public function getLicense(): ?string
    {
        return $this->license;
    }

    /**
     * Set the value of license
     */
    public function setLicense(?string $license): self
    {
        $this->license = $license;

        return $this;
    }

    /**
     * Returns the full name
     */
    public function getFullName(): string
    {
        return trim($this->name.' '.$this->surname);
    }

    public function getFullNameAndLicense(): string
    {
        return trim($this->name.' '.$this->surname.' ['.$this->license . ']');
    }

}
