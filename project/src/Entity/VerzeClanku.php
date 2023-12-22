<?php

namespace App\Entity;

use App\Repository\VerzeClankuRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: VerzeClankuRepository::class)]
class VerzeClanku
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\ManyToOne]
    #[ORM\JoinColumn(nullable: false)]
    private ?Clanek $clanek = null;

    #[ORM\Column(length: 10)]
    private ?string $datum_nahrani = null;

    #[ORM\Column(length: 1000)]
    private ?string $soubor_clanek = null;

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

    public function getDatumNahrani(): ?string
    {
        return $this->datum_nahrani;
    }

    public function setDatumNahrani(string $datum_nahrani): static
    {
        $this->datum_nahrani = $datum_nahrani;

        return $this;
    }

    public function getSouborClanek(): ?string
    {
        return $this->soubor_clanek;
    }

    public function setSouborClanek(string $soubor_clanek): static
    {
        $this->soubor_clanek = $soubor_clanek;

        return $this;
    }
}