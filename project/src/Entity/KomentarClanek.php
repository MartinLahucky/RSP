<?php

namespace App\Entity;

use App\Repository\KomentarClanekRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: KomentarClanekRepository::class)]
class KomentarClanek
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\ManyToOne]
    #[ORM\JoinColumn(nullable: false)]
    private ?VerzeClanku $verze_clanku_id = null;

    #[ORM\ManyToOne]
    #[ORM\JoinColumn(nullable: false)]
    private ?User $user_id = null;

    #[ORM\Column(type: Types::DATE_MUTABLE)]
    private ?\DateTimeInterface $datum = null;

    #[ORM\Column(length: 10000)]
    private ?string $text = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getVerzeClankuId(): ?VerzeClanku
    {
        return $this->verze_clanku_id;
    }

    public function setVerzeClankuId(?VerzeClanku $verze_clanku_id): static
    {
        $this->verze_clanku_id = $verze_clanku_id;

        return $this;
    }

    public function getUserId(): ?User
    {
        return $this->user_id;
    }

    public function setUserId(?User $user_id): static
    {
        $this->user_id = $user_id;

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
