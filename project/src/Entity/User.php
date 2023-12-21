<?php

namespace App\Entity;

use App\Repository\UserRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Security\Core\User\PasswordAuthenticatedUserInterface;
use Symfony\Component\Security\Core\User\UserInterface;

enum Role: string
{
    case ADMIN = "ADMIN";
    case SEFREDAKTOR = "SEFREDAKTOR";
    case REDAKTOR = "REDAKTOR";
    case RECENZENT = "RECENZENT";
    case AUTOR = "AUTOR";
}

#[ORM\Entity(repositoryClass: UserRepository::class)]
#[UniqueEntity(fields: ['email'], message: 'There is already an account with this email')]
class User implements UserInterface, PasswordAuthenticatedUserInterface
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 180, unique: true)]
    private ?string $email = null;

    #[ORM\Column]
    private array $roles = [];

    #[ORM\Column]
    private ?string $firstname = null;

    #[ORM\Column]
    private ?string $lastname = null;

    /**
     * @var string The hashed password
     */
    #[ORM\Column]
    private ?string $password = null;

    //#[ORM\ManyToMany(targetEntity: Ukol::class, mappedBy: 'user')]
    //private Collection $ukoly;

    public function __construct()
    {
        $this->ukoly = new ArrayCollection();
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

    public function getFirstName(): ?string
    {
        return $this->firstname;
    }

    public function setFirstName(string $firstname): static
    {
        $this->firstname = $firstname;

        return $this;
    }

    public function getLastName(): ?string
    {
        return $this->lastname;
    }

    public function setLastName(string $lastname): static
    {
        $this->lastname = $lastname;

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
     */
    public function getRoles(): array
    {
        $roles = $this->roles;
        // guarantee every user at least has ROLE_USER
        $roles[] = "AUTOR";

        return array_unique($roles);
    }

    public function setRoles(array $roles): static
    {
        $this->roles = $roles;

        return $this;
    }

    public function isAdmin(): bool
    {
        foreach ($this->roles as $role) {
            if ($role === \App\Entity\Role::ADMIN->value) {
                return true;
            }
        }

        return false;
    }

    public function isRecenzent(): bool
    {
        foreach ($this->roles as $role) {
            if ($role === \App\Entity\Role::RECENZENT->value) {
                return true;
            }
        }

        return false;
    }

    public function isRedaktor(): bool
    {
        foreach ($this->roles as $role) {
            if ($role === \App\Entity\Role::REDAKTOR->value) {
                return true;
            }
        }

        return false;
    }

    public function isSefredaktor(): bool
    {
        foreach ($this->roles as $role) {
            if ($role === \App\Entity\Role::SEFREDAKTOR->value) {
                return true;
            }
        }

        return false;
    }

    public function isAutor(): bool
    {
        // Assuming $this->roles is an array of Role enum objects
        if (!$this->roles)
        {
            return true; // Protoze default role je autor
        }

        foreach ($this->roles as $role) {
            if ($role === \App\Entity\Role::AUTOR->value) {
                return true;
            }
        }

        return false;
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

    /**
     * @return Collection<int, Ukol>
     */
    /*public function getUkoly(): Collection
    {
        return $this->ukoly;
    }

    public function addUkoly(Ukol $ukoly): static
    {
        if (!$this->ukoly->contains($ukoly)) {
            $this->ukoly->add($ukoly);
            $ukoly->addUser($this);
        }

        return $this;
    }

    public function removeUkoly(Ukol $ukoly): static
    {
        if ($this->ukoly->removeElement($ukoly)) {
            $ukoly->removeUser($this);
        }

        return $this;
    }*/
}
