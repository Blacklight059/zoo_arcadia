<?php

namespace App\Entity;

use App\Repository\UserRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Security\Core\User\PasswordAuthenticatedUserInterface;
use Symfony\Component\Security\Core\User\UserInterface;

#[ORM\Entity(repositoryClass: UserRepository::class)]
#[ORM\UniqueConstraint(name: 'UNIQ_IDENTIFIER_EMAIL', fields: ['email'])]
class User implements UserInterface, PasswordAuthenticatedUserInterface
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 180)]
    private ?string $email = null;

    /**
     * @var list<string> The user roles
     */
    #[ORM\Column]
    private array $roles = [];

    /**
     * @var string The hashed password
     */
    #[ORM\Column]
    private ?string $password = null;

    #[ORM\Column(length: 255)]
    private ?string $name = null;

    #[ORM\Column(length: 255)]
    private ?string $firstname = null;

    /**
     * @var Collection<int, VetReport>
     */
    #[ORM\OneToMany(targetEntity: VetReport::class, mappedBy: 'veterinarian')]
    private Collection $vetReports;

    /**
     * @var Collection<int, HabitatComment>
     */
    #[ORM\OneToMany(targetEntity: HabitatComment::class, mappedBy: 'veterinarian')]
    private Collection $habitatComments;

    public function __construct()
    {
        $this->vetReports = new ArrayCollection();
        $this->habitatComments = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getEmail(): ?string
    {
        return $this->email;
    }

    public function setEmail(string $email): static
    {
        $this->email = $email;

        return $this;
    }

    /**
     * A visual identifier that represents this user.
     *
     * @see UserInterface
     */
    public function getUserIdentifier(): string
    {
        return (string) $this->email;
    }

    /**
     * @see UserInterface
     *
     * @return list<string>
     */
    public function getRoles(): array
    {
        $roles = $this->roles;
        // guarantee every user at least has ROLE_USER
        $roles[] = 'ROLE_USER';

        return array_unique($roles);
    }

    /**
     * @param list<string> $roles
     */
    public function setRoles(array $roles): static
    {
        $this->roles = $roles;

        return $this;
    }

    /**
     * @see PasswordAuthenticatedUserInterface
     */
    public function getPassword(): string
    {
        return $this->password;
    }

    public function setPassword(string $password): static
    {
        $this->password = $password;

        return $this;
    }

    /**
     * @see UserInterface
     */
    public function eraseCredentials(): void
    {
        // If you store any temporary, sensitive data on the user, clear it here
        // $this->plainPassword = null;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name): static
    {
        $this->name = $name;

        return $this;
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
            $vetReport->setVeterinarian($this);
        }

        return $this;
    }

    public function removeVetReport(VetReport $vetReport): static
    {
        if ($this->vetReports->removeElement($vetReport)) {
            // set the owning side to null (unless already changed)
            if ($vetReport->getVeterinarian() === $this) {
                $vetReport->setVeterinarian(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, HabitatComment>
     */
    public function getHabitatComments(): Collection
    {
        return $this->habitatComments;
    }

    public function addHabitatComment(HabitatComment $habitatComment): static
    {
        if (!$this->habitatComments->contains($habitatComment)) {
            $this->habitatComments->add($habitatComment);
            $habitatComment->setVeterinarian($this);
        }

        return $this;
    }

    public function removeHabitatComment(HabitatComment $habitatComment): static
    {
        if ($this->habitatComments->removeElement($habitatComment)) {
            // set the owning side to null (unless already changed)
            if ($habitatComment->getVeterinarian() === $this) {
                $habitatComment->setVeterinarian(null);
            }
        }

        return $this;
    }

}
