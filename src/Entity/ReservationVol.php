<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;

use App\Repository\ReservationVolRepository;

#[ORM\Entity(repositoryClass: ReservationVolRepository::class)]
#[ORM\Table(name: 'reservation_vol')]
class ReservationVol
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    private ?int $id = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function setId(int $id): self
    {
        $this->id = $id;
        return $this;
    }

    #[ORM\ManyToOne(targetEntity: User::class, inversedBy: 'reservationVols')]
    #[ORM\JoinColumn(name: 'id_user', referencedColumnName: 'id')]
    private ?User $user = null;

    public function getUser(): ?User
    {
        return $this->user;
    }

    public function setUser(?User $user): self
    {
        $this->user = $user;
        return $this;
    }

    #[ORM\ManyToOne(targetEntity: Vol::class, inversedBy: 'reservationVols')]
    #[ORM\JoinColumn(name: 'id_vol', referencedColumnName: 'id')]
    private ?Vol $vol = null;

    public function getVol(): ?Vol
    {
        return $this->vol;
    }

    public function setVol(?Vol $vol): self
    {
        $this->vol = $vol;
        return $this;
    }

    #[ORM\Column(type: 'string', nullable: false)]
    private ?string $classe = null;

    public function getClasse(): ?string
    {
        return $this->classe;
    }

    public function setClasse(string $classe): self
    {
        $this->classe = $classe;
        return $this;
    }

    #[ORM\Column(type: 'integer', nullable: false)]
    private ?int $nb_billets = null;

    public function getNb_billets(): ?int
    {
        return $this->nb_billets;
    }

    public function setNb_billets(int $nb_billets): self
    {
        $this->nb_billets = $nb_billets;
        return $this;
    }

    public function getNbBillets(): ?int
    {
        return $this->nb_billets;
    }

    public function setNbBillets(int $nb_billets): static
    {
        $this->nb_billets = $nb_billets;

        return $this;
    }

}
