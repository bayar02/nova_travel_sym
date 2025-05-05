<?php

namespace App\Entity;

use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;

use App\Repository\ReservationHebergementRepository;

#[ORM\Entity(repositoryClass: ReservationHebergementRepository::class)]
#[ORM\Table(name: 'reservation_hebergement')]
class ReservationHebergement
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

    #[ORM\ManyToOne(targetEntity: User::class, inversedBy: 'reservationHebergements')]
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

    #[ORM\ManyToOne(targetEntity: Hebergement::class, inversedBy: 'reservationHebergements')]
    #[ORM\JoinColumn(name: 'id_hebergement', referencedColumnName: 'id')]
    private ?Hebergement $hebergement = null;

    public function getHebergement(): ?Hebergement
    {
        return $this->hebergement;
    }

    public function setHebergement(?Hebergement $hebergement): self
    {
        $this->hebergement = $hebergement;
        return $this;
    }

<<<<<<< HEAD
    #[ORM\Column(type: 'date', nullable: false)]
=======
    #[ORM\Column(type: Types::DATETIME_MUTABLE, nullable: true)]
>>>>>>> f5842df (Initial commit for Events branch)
    private ?\DateTimeInterface $date_debut = null;

    public function getDate_debut(): ?\DateTimeInterface
    {
        return $this->date_debut;
    }

    public function setDate_debut(\DateTimeInterface $date_debut): self
    {
        $this->date_debut = $date_debut;
        return $this;
    }

<<<<<<< HEAD
    #[ORM\Column(type: 'date', nullable: false)]
=======
    #[ORM\Column(type: Types::DATETIME_MUTABLE, nullable: true)]
>>>>>>> f5842df (Initial commit for Events branch)
    private ?\DateTimeInterface $date_fin = null;

    public function getDate_fin(): ?\DateTimeInterface
    {
        return $this->date_fin;
    }

    public function setDate_fin(\DateTimeInterface $date_fin): self
    {
        $this->date_fin = $date_fin;
        return $this;
    }

    #[ORM\Column(type: 'integer', nullable: false)]
    private ?int $nb_perso = null;

    public function getNb_perso(): ?int
    {
        return $this->nb_perso;
    }

    public function setNb_perso(int $nb_perso): self
    {
        $this->nb_perso = $nb_perso;
        return $this;
    }

    public function getDateDebut(): ?\DateTimeInterface
    {
        return $this->date_debut;
    }

    public function setDateDebut(\DateTimeInterface $date_debut): static
    {
        $this->date_debut = $date_debut;

        return $this;
    }

    public function getDateFin(): ?\DateTimeInterface
    {
        return $this->date_fin;
    }

    public function setDateFin(\DateTimeInterface $date_fin): static
    {
        $this->date_fin = $date_fin;

        return $this;
    }

    public function getNbPerso(): ?int
    {
        return $this->nb_perso;
    }

    public function setNbPerso(int $nb_perso): static
    {
        $this->nb_perso = $nb_perso;

        return $this;
    }

}
