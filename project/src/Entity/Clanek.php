<?php

namespace App\Entity;

use App\Repository\ClanekRepository;
use Doctrine\ORM\Mapping as ORM;

enum StavAutor: string
{
    case PODANO = "PODANO"; // svetle modra
    case TEMATICKA_NEVHODNOST = "TEMATICKA NEVHODNOST"; // červena
    case PREDANO_RECENZENTUM = "PREDANO RECENZENTUM"; //zluta
    case ZAMITNUTO = "ZAMITNUTO"; //cerna
    case PRIJATO_S_VYHRADAMI = "PRIJATO S VYHRADAMI"; //zlutozelena
    case OPRAVA_AUTORA = "OPRAVA AUTORA"; //ruzova
    case DODATECNE_VYJADRENI_AUTORA = "DODATECNE VYJADRENI AUTORA"; //fialová
    case VYJADRENI_SEFREDAKTORA = "CEKANI NA VYJADRENI SEFREDAKTORA"; //tyrkysova
    case PRIJATO = "PRIJATO"; //zelena
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

    #[ORM\ManyToOne]
    #[ORM\JoinColumn(nullable: false)]
    private ?RecenzniRizeni $recenzni_rizeni = null;

    #[ORM\Column(length: 50)]
    private ?string $stav_redakce = null;

    #[ORM\Column(length: 50)]
    private ?string $stav_autor = null;

    #[ORM\Column(length: 50)]
    private ?string $nazev_clanku = null;

    #[ORM\ManyToOne]
    #[ORM\JoinColumn(nullable: false)]
    private ?User $user = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getRecenzniRizeni(): ?RecenzniRizeni
    {
        return $this->recenzni_rizeni;
    }

    public function setRecenzniRizeni(?RecenzniRizeni $recenzni_rizeni): static
    {
        $this->recenzni_rizeni = $recenzni_rizeni;

        return $this;
    }

    public function getStavRedakce(): ?string
    {
        return $this->stav_redakce;
    }

    public function setStavRedakce(string $stav_redakce): static
    {
        $this->stav_redakce = $stav_redakce;

        return $this;
    }

    public function getNazevClanku(): ?string
    {
        return $this->nazev_clanku;
    }

    public function setNazevClanku(string $nazev_clanku): static
    {
        $this->nazev_clanku = $nazev_clanku;

        return $this;
    }

    public function getStavAutor(): ?string
    {
        return $this->stav_autor;
    }

    public function setStavAutor(string $stav_autor): static
    {
        $this->stav_autor = $stav_autor;

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
}