<?php

namespace App\Entity;

use App\Repository\NamitkaRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: NamitkaRepository::class)]
class Namitka
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\OneToOne(cascade: ['persist', 'remove'])]
    #[ORM\JoinColumn(nullable: false)]
    private ?Clanek $clanek = null;

    #[ORM\Column(length: 10)]
    private ?string $datum = null;

    #[ORM\Column(length: 10000)]
    private ?string $text_namitky = null;

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

    public function getDatum(): ?string
    {
        return $this->datum;
    }

    public function setDatum(string $datum): static
    {
        $this->datum = $datum;

        return $this;
    }

    public function getTextNamitky(): ?string
    {
        return $this->text_namitky;
    }

    public function setTextNamitky(string $text_namitky): static
    {
        $this->text_namitky = $text_namitky;

        return $this;
    }
}
