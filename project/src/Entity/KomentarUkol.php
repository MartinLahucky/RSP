<?php

namespace App\Entity;

use App\Repository\KomentarUkolRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: KomentarUkolRepository::class)]
class KomentarUkol
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\ManyToOne]
    #[ORM\JoinColumn(nullable: false)]
    private ?VerzeClanku $verze_clanku = null;
    //private ?Ukol $ukol = null;

    #[ORM\ManyToOne]
    #[ORM\JoinColumn(nullable: false)]
    private ?User $user = null;

    #[ORM\Column(length: 10)]
    private ?string $datum = null;

    #[ORM\Column(length: 10000)]
    private ?string $text = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    /*public function getUkol(): ?Ukol
    {
        return $this->ukol;
    }

    public function setUkol(?Ukol $ukol): static
    {
        $this->ukol = $ukol;

        return $this;
    }*/

    public function getVerzeClanku(): ?VerzeClanku
    {
        return $this->verze_clanku;
    }

    public function setVerzeClanku(VerzeClanku $verzeClanku): static
    {
        $this->verze_clanku = $verzeClanku;
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

    public function getDatum(): ?string
    {
        return $this->datum;
    }

    public function setDatum(string $datum): static
    {
        $this->datum = $datum;

        return $this;
    }

    public function getText(): ?string
    {
        return $this->text;
    }

    public function setText(string $text): static
    {
        $this->text = $text;

        return $this;
    }
}
