<?php

namespace App\Document;

use Doctrine\ODM\MongoDB\Mapping\Annotations as MongoDB;

#[MongoDB\Document(collection: 'animalVisit')]

class AnimalVisit
{
    
    #[MongoDB\Id]
    private $id;

    #[MongoDB\Field(type: 'string')]
    private $animalName;

    #[MongoDB\Field(type: 'int')]
    private $visits = 0;

    public function getId(): ?string
    {
        return $this->id;
    }

    public function getAnimalName(): ?string
    {
        return $this->animalName;
    }

    public function setAnimalName(string $animalName): self
    {
        $this->animalName = $animalName;

        return $this;
    }

    public function getVisits(): ?int
    {
        return $this->visits;
    }

    public function incrementVisits(): self
    {
        $this->visits++;

        return $this;
    }
}
