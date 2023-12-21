<?php

namespace App\Entity;

use App\Repository\UkolRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: UkolRepository::class)]
class Ukol
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\OneToOne]
    #[ORM\JoinColumn(nullable: false)]
    private ?Clanek $clanek = null;

    /*#[ORM\ManyToMany(targetEntity: User::class, inversedBy: 'ukoly')]
    private Collection $user;*/

    #[ORM\ManyToOne]
    #[ORM\JoinColumn(nullable: false)]
    private ?User $user = null;

    #[ORM\Column(length: 10)]
    private ?string $deadline = null;

    public function __construct()
    {
        //$this->user = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getClanek(): ?Clanek
    {
        return $this->clanek;
    }

    public function setClanek(Clanek $clanek): static
    {
        $this->clanek = $clanek;

        return $this;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(string $description): static
    {
        $this->description = $description;

        return $this;
    }

    public function getUser(): User
    {
        return $this->user;
    }

    public function setUser(User $user): static
    {
        $this->user = $user;
        return $this;
    }

    /*public function addUser(User $user): static
    {
        if (!$this->user->contains($user)) {
            $this->user->add($user);
        }

        return $this;
    }

    public function removeUser(User $user): static
    {
        $this->user->removeElement($user);

        return $this;
    }*/

    public function getDeadline(): ?string    {
        return $this->deadline;
    }

    public function setDeadline(string $deadline): static
    {
        $this->deadline = $deadline;

        return $this;
    }
}
