<?php

namespace App\Entity;

use App\Repository\ClanekRepository;
use Doctrine\ORM\Mapping as ORM;

enum StavAutor: string
{
    case PODANO = "PODANO";
    case TEMATICKA_NEVHODNOST = "TEMATICKA NEVHODNOST";
    case PREDANO_RECENZENTUM = "PREDANO RECENZENTUM";
    case ZAMITNUTO = "ZAMITNUTO";
    case PRIJATO_S_VYHRADAMI = "PRIJATO S VYHRADAMI";
    case OPRAVA_AUTORA = "OPRAVA AUTORA";
    case DODATECNE_VYJADRENI_AUTORA = "DODATECNE VYJADRENI AUTORA";
    case VYJADRENI_SEFREDAKTORA = "CEKANI NA VYJADRENI SEFREDAKTORA";
    case PRIJATO = "PRIJATO";
}

enum StavRedakce: string
{
    case NOVE_PODANY = "NOVE PODANY";
    case CEKA_NA_STANOVENI_RECENZENTU = "CEKA NA STANOVENI RECENZENTU";
    case POSUDEK_1_DORUCEN = "POSUDEK 1 DORUCEN";
    case POSUDEK_2_DORUCEN = "POSUDEK 2 DORUCEN";
    case POSUDKY_ODESLANY_AUTOROVI = "POSUDKY ODESLANY AUTOROVI";
    case UPRAVA_TEXTU_AUTOREM = "UPRAVA TEXTU AUTOREM";
    case PRIJATO = "PRIJATO";
    case ZAMITNUTO = "ZAMITNUTO";
}

#[ORM\Entity(repositoryClass: ClanekRepository::class)]
class Clanek
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\ManyToOne(inversedBy: 'stav_autor')]
    #[ORM\JoinColumn(nullable: false)]
    private ?RecenzniRizeni $recenzni_rizeni = null;

    #[ORM\Column(length: 50)]
    private ?string $stav_redakce = null;

    #[ORM\Column(length: 50)]
    private ?string $stav_autor = null;

    #[ORM\ManyToOne]
    #[ORM\JoinColumn(nullable: false)]
    private ?User $user = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getIdRecenzniRizeni(): ?RecenzniRizeni
    {
        return $this->recenzni_rizeni;
    }

    public function setIdRecenzniRizeni(?RecenzniRizeni $recenzni_rizeni): static
    {
        $this->recenzni_rizeni = $recenzni_rizeni;

        return $this;
    }

    public function getStavRedakce(): ?string
    {
        return $this->stav_redakce;
    }

    public function setStavRedakce(int $stav_redakce): static
    {
        $this->stav_redakce = $stav_redakce;

        return $this;
    }

    public function getStavAutor(): ?string
    {
        return $this->stav_autor;
    }

    public function setStavAutor(int $stav_autor): static
    {
        $this->stav_autor = $stav_autor;

        return $this;
    }

    public function getUserId(): ?User
    {
        return $this->user;
    }

    public function setUserId(?User $user): static
    {
        $this->user = $user;

        return $this;
    }
}