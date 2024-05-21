<?php

namespace App\Entity;

use App\Repository\AnimalRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: AnimalRepository::class)]
class Animal
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $firstname = null;

    #[ORM\Column(length: 255)]
    private ?string $breed = null;

    #[ORM\ManyToOne(inversedBy: 'animals')]
    private ?Habitat $habitat = null;

    #[ORM\OneToMany(targetEntity: Image::class, mappedBy: 'animal', orphanRemoval: true, cascade: ['persist'])]
    private Collection $images;

    /**
     * @var Collection<int, VetReport>
     */
    #[ORM\OneToMany(targetEntity: VetReport::class, mappedBy: 'animal')]
    private Collection $vetReports;

    #[ORM\OneToMany(mappedBy: 'animal', targetEntity: FoodConsumption::class, orphanRemoval: true)]
    private Collection $foodConsumptions;

    public function __construct()
    {
        $this->images = new ArrayCollection();
        $this->vetReports = new ArrayCollection();
        $this->foodConsumptions = new ArrayCollection();

    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getFirstname(): ?string
    {
        return $this->firstname;
    }

    public function setFirstname(string $firstname): static
    {
        $this->firstname = $firstname;

        return $this;
    }

    public function getBreed(): ?string
    {
        return $this->breed;
    }

    public function setBreed(string $breed): static
    {
        $this->breed = $breed;

        return $this;
    }

    public function getHabitat(): ?Habitat
    {
        return $this->habitat;
    }

    public function setHabitat(?Habitat $habitat): static
    {
        $this->habitat = $habitat;

        return $this;
    }

    /**
     * @return Collection|Image[]
     */
    public function getImages(): Collection
    {
        return $this->images ?? new ArrayCollection();
    }

    public function addImage(Image $image): self
    {
        if (!$this->images->contains($image)) {
            $this->images[] = $image;
            $image->setAnimal($this);
        }

        return $this;
    }

    public function removeImage(Image $image): self
    {
        if ($this->images->removeElement($image)) {
            if ($image->getAnimal() === $this) {
                $image->setAnimal(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, VetReport>
     */
    public function getVetReports(): Collection
    {
        return $this->vetReports;
    }

    public function addVetReport(VetReport $vetReport): static
    {
        if (!$this->vetReports->contains($vetReport)) {
            $this->vetReports->add($vetReport);
            $vetReport->setAnimal($this);
        }

        return $this;
    }

    public function removeVetReport(VetReport $vetReport): static
    {
        if ($this->vetReports->removeElement($vetReport)) {
            // set the owning side to null (unless already changed)
            if ($vetReport->getAnimal() === $this) {
                $vetReport->setAnimal(null);
            }
        }

        return $this;
    }

    public function getFoodConsumptions(): Collection
    {
        return $this->foodConsumptions;
    }

    public function addFoodConsumption(FoodConsumption $foodConsumption): self
    {
        if (!$this->foodConsumptions->contains($foodConsumption)) {
            $this->foodConsumptions[] = $foodConsumption;
            $foodConsumption->setAnimal($this);
        }

        return $this;
    }

    public function removeFoodConsumption(FoodConsumption $foodConsumption): self
    {
        if ($this->foodConsumptions->removeElement($foodConsumption)) {
            // set the owning side to null (unless already changed)
            if ($foodConsumption->getAnimal() === $this) {
                $foodConsumption->setAnimal(null);
            }
        }

        return $this;
    }
}
