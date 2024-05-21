<?php

namespace App\Entity;

use App\Repository\HabitatCommentRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: HabitatCommentRepository::class)]
class HabitatComment
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(type: Types::TEXT)]
    private ?string $comment = null;

    #[ORM\Column(type: Types::DATETIME_MUTABLE)]
    private ?\DateTimeInterface $commentDate = null;

    #[ORM\ManyToOne(inversedBy: 'habitatComments')]
    private ?Habitat $habitat = null;

    #[ORM\ManyToOne(inversedBy: 'habitatComments')]
    private ?User $veterinarian = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getComment(): ?string
    {
        return $this->comment;
    }

    public function setComment(string $comment): static
    {
        $this->comment = $comment;

        return $this;
    }

    public function getCommentDate(): ?\DateTimeInterface
    {
        return $this->commentDate;
    }

    public function setCommentDate(\DateTimeInterface $commentDate): static
    {
        $this->commentDate = $commentDate;

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
