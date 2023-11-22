<?php

namespace App\Entity;

use App\Repository\PosudekRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: PosudekRepository::class)]
class Posudek
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\ManyToOne]
    #[ORM\JoinColumn(nullable: false)]
    private ?Clanek $clanek = null;

    #[ORM\ManyToOne]
    #[ORM\JoinColumn(nullable: false)]
    private ?User $user = null;

    #[ORM\Column(length: 1000)]
    private ?string $posudek_soubor = null;

    #[ORM\Column]
    private ?bool $zpristupnen_autorovi = null;

    #[ORM\Column]
    private ?int $aktualnost = null;

    #[ORM\Column]
    private ?int $zajimavost = null;

    #[ORM\Column]
    private ?int $originalita = null;

    #[ORM\Column]
    private ?int $odborna_uroven = null;

    #[ORM\Column]
    private ?int $jazykova_uroven = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getClanek(): ?Clanek
    {
        return $this->clanek;
    }

    public function setClanek(?Clanek $clanek): static
    {
        $this->clanek = $clanek;

        return $this;
    }

    public function getUser(): ?User
    {
        return $this->user;
    }

    public function setUser(?User $user): static
    {
        $this->user = $user;

        return $this;
    }

    public function getPosudekSoubor(): ?string
    {
        return $this->posudek_soubor;
    }

    public function setPosudekSoubor(string $posudek_soubor): static
    {
        $this->posudek_soubor = $posudek_soubor;

        return $this;
    }

    public function isZpristupnenAutorovi(): ?bool
    {
        return $this->zpristupnen_autorovi;
    }

    public function setZpristupnenAutorovi(bool $zpristupnen_autorovi): static
    {
        $this->zpristupnen_autorovi = $zpristupnen_autorovi;

        return $this;
    }

    public function getAktualnost(): ?int
    {
        return $this->aktualnost;
    }

    public function setAktualnost(int $aktualnost): static
    {
        $this->aktualnost = $aktualnost;

        return $this;
    }

    public function getZajimavost(): ?int
    {
        return $this->zajimavost;
    }

    public function setZajimavost(int $zajimavost): static
    {
        $this->zajimavost = $zajimavost;

        return $this;
    }

    public function getOriginalita(): ?int
    {
        return $this->originalita;
    }

    public function setOriginalita(int $originalita): static
    {
        $this->originalita = $originalita;

        return $this;
    }

    public function getOdbornaUroven(): ?int
    {
        return $this->odborna_uroven;
    }

    public function setOdbornaUroven(int $odborna_uroven): static
    {
        $this->odborna_uroven = $odborna_uroven;

        return $this;
    }

    public function getJazykovaUroven(): ?int
    {
        return $this->jazykova_uroven;
    }

    public function setJazykovaUroven(int $jazykova_uroven): static
    {
        $this->jazykova_uroven = $jazykova_uroven;

        return $this;
    }
}
