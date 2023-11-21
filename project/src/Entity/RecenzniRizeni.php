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

    #[ORM\OneToOne(cascade: ['persist', 'remove'])]
    #[ORM\JoinColumn(nullable: false)]
    private ?Tisk $tisk = null;

    #[ORM\Column(length: 10)]
    private ?string $od = null;

    #[ORM\Column(length: 10)]
    private ?string $do = null;

    #[ORM\OneToMany(mappedBy: 'id_recenzni_rizeni', targetEntity: Clanek::class, orphanRemoval: true)]
    private Collection $clanky;

    public function __construct()
    {
        $this->clanky = new ArrayCollection();
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

    /**
     * @return Collection<int, Clanek>
     */
    public function getClanek(): Collection
    {
        return $this->clanky;
    }

    public function addClanek(Clanek $clanek): static
    {
        if (!$this->clanky->contains($clanek)) {
            $this->clanky->add($clanek);
            $clanek->setIdRecenzniRizeni($this);
        }

        return $this;
    }

    public function removeClanek(Clanek $clanek): static
    {
        if ($this->clanky->removeElement($clanek)) {
            // set the owning side to null (unless already changed)
            if ($clanek->getIdRecenzniRizeni() === $this) {
                $clanek->setIdRecenzniRizeni(null);
            }
        }

        return $this;
    }
}
