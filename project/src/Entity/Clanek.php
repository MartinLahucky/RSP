<?php

namespace App\Entity;

use App\Repository\ClanekRepository;
use Doctrine\ORM\Mapping as ORM;

enum StavAutor: int
{
    case PODANO = 0;
    case TEMATICKA_NEVHODNOST = 1;
    case PREDANO_RECENZENTUM = 2;
    case ZAMITNUTO = 3;
    case PRIJATO_S_VYHRADAMI = 4;
    case OPRAVA_AUTORA = 5;
    case DODATECNE_VYJADRENI_AUTORA = 6;
    case VYJADRENI_SEFREDAKTORA = 7;  // Cekani na vyjadreni od sefredaktora
    case PRIJATO = 8;
}

enum StavRedakce: int
{
    case NOVE_PODANY = 0;
    case CEKA_NA_STANOVENI_RECENZENTU = 1;
    case POSUDEK_1_DORUCEN = 2;
    case POSUDEK_2_DORUCEN = 3;
    case POSUDKY_ODESLANY_AUTOROVI = 4;
    case UPRAVA_TEXTU_AUTOREM = 5;
    case PRIJATO = 6;
    case ZAMITNUTO = 7;
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
    private ?RecenzniRizeni $id_recenzni_rizeni = null;

    #[ORM\Column]
    private ?int $stav_redakce = null;

    #[ORM\Column]
    private ?int $stav_autor = null;

    #[ORM\ManyToOne]
    #[ORM\JoinColumn(nullable: false)]
    private ?User $user_id = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getIdRecenzniRizeni(): ?RecenzniRizeni
    {
        return $this->id_recenzni_rizeni;
    }

    public function setIdRecenzniRizeni(?RecenzniRizeni $id_recenzni_rizeni): static
    {
        $this->id_recenzni_rizeni = $id_recenzni_rizeni;

        return $this;
    }

    public function getStavRedakce(): ?int
    {
        return $this->stav_redakce;
    }

    public function setStavRedakce(int $stav_redakce): static
    {
        $this->stav_redakce = $stav_redakce;

        return $this;
    }

    public function getStavAutor(): ?int
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
        return $this->user_id;
    }

    public function setUserId(?User $user_id): static
    {
        $this->user_id = $user_id;

        return $this;
    }
}
