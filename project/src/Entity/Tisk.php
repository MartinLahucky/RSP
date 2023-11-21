<?php

namespace App\Entity;

use App\Repository\TiskRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: TiskRepository::class)]
class Tisk
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 10)]
    private ?string $datum = null;

    #[ORM\Column]
    private ?int $kapacita = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getDatum(): ?string
    {
        return $this->datum;
    }

    public function setDatum(string $datum): static
    {
        $this->datum = $datum;

        return $this;
    }

    public function getKapacita(): ?int
    {
        return $this->kapacita;
    }

    public function setKapacita(int $kapacita): static
    {
        $this->kapacita = $kapacita;

        return $this;
    }
}
