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
    private ?Clanek $clanek_id = null;

    #[ORM\Column(type: Types::DATE_MUTABLE)]
    private ?\DateTimeInterface $datum = null;

    #[ORM\Column(length: 10000)]
    private ?string $text_namitky = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getClanekId(): ?Clanek
    {
        return $this->clanek_id;
    }

    public function setClanekId(Clanek $clanek_id): static
    {
        $this->clanek_id = $clanek_id;

        return $this;
    }

    public function getDatum(): ?\DateTimeInterface
    {
        return $this->datum;
    }

    public function setDatum(\DateTimeInterface $datum): static
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
