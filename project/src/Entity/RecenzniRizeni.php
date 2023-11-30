<?php

namespace App\Entity;

use App\Repository\RecenzniRizeniRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: RecenzniRizeniRepository::class)]
class RecenzniRizeni
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\OneToOne]
    #[ORM\JoinColumn(nullable: false)]
    private ?Tisk $tisk = null;

    #[ORM\Column(length: 10)]
    private ?string $od = null;

    #[ORM\Column(length: 10)]
    private ?string $do = null;

    public function __construct()
    {

    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getTisk(): ?Tisk
    {
        return $this->tisk;
    }

    public function setTisk(Tisk $tisk): static
    {
        $this->tisk = $tisk;

        return $this;
    }

    public function getOd(): ?string
    {
        return $this->od;
    }

    public function setOd(string $od): static
    {
        $this->od = $od;

        return $this;
    }

    public function getDo(): ?string
    {
        return $this->do;
    }

    public function setDo(string $do): static
    {
        $this->do = $do;

        return $this;
    }
}