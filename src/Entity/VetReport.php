<?php

namespace App\Entity;

use App\Repository\VetReportRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: VetReportRepository::class)]
class VetReport
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $animalState = null;

    #[ORM\Column(length: 255)]
    private ?string $foodOffered = null;

    #[ORM\Column]
    private ?float $foodWeight = null;

    #[ORM\Column(type: Types::DATETIME_MUTABLE)]
    private ?\DateTimeInterface $visitDate = null;

    #[ORM\Column(type: Types::TEXT, nullable: true)]
    private ?string $stateDetails = null;

    #[ORM\ManyToOne(inversedBy: 'vetReports')]
    private ?Animal $animal = null;

    #[ORM\ManyToOne(inversedBy: 'vetReports')]
    private ?User $veterinarian = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getAnimalState(): ?string
    {
        return $this->animalState;
    }

    public function setAnimalState(string $animalState): static
    {
        $this->animalState = $animalState;

        return $this;
    }

    public function getFoodOffered(): ?string
    {
        return $this->foodOffered;
    }

    public function setFoodOffered(string $foodOffered): static
    {
        $this->foodOffered = $foodOffered;

        return $this;
    }

    public function getFoodWeight(): ?float
    {
        return $this->foodWeight;
    }

    public function setFoodWeight(float $foodWeight): static
    {
        $this->foodWeight = $foodWeight;

        return $this;
    }

    public function getVisitDate(): ?\DateTimeInterface
    {
        return $this->visitDate;
    }

    public function setVisitDate(\DateTimeInterface $visitDate): static
    {
        $this->visitDate = $visitDate;

        return $this;
    }

    public function getStateDetails(): ?string
    {
        return $this->stateDetails;
    }

    public function setStateDetails(?string $stateDetails): static
    {
        $this->stateDetails = $stateDetails;

        return $this;
    }

    public function getAnimal(): ?Animal
    {
        return $this->animal;
    }

    public function setAnimal(?Animal $animal): static
    {
        $this->animal = $animal;

        return $this;
    }

    public function getVeterinarian(): ?User
    {
        return $this->veterinarian;
    }

    public function setVeterinarian(?User $veterinarian): static
    {
        $this->veterinarian = $veterinarian;

        return $this;
    }
}
