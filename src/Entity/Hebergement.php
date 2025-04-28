<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;

use App\Repository\HebergementRepository;

#[ORM\Entity(repositoryClass: HebergementRepository::class)]
#[ORM\Table(name: 'hebergement')]
class Hebergement
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

    #[ORM\Column(type: 'string', nullable: false)]
    private ?string $type = null;

    public function getType(): ?string
    {
        return $this->type;
    }

    public function setType(string $type): self
    {
        $this->type = $type;
        return $this;
    }

    #[ORM\Column(type: 'string', nullable: false)]
    private ?string $nom = null;

    public function getNom(): ?string
    {
        return $this->nom;
    }

    public function setNom(string $nom): self
    {
        $this->nom = $nom;
        return $this;
    }

    #[ORM\Column(type: 'string', nullable: false)]
    private ?string $adresse = null;

    public function getAdresse(): ?string
    {
        return $this->adresse;
    }

    public function setAdresse(string $adresse): self
    {
        $this->adresse = $adresse;
        return $this;
    }

    #[ORM\Column(type: 'string', nullable: false)]
    private ?string $description = null;

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(string $description): self
    {
        $this->description = $description;
        return $this;
    }

    #[ORM\Column(type: 'float', nullable: false)]
    private ?float $prix_nuit = null;

    public function getPrix_nuit(): ?float
    {
        return $this->prix_nuit;
    }

    public function setPrix_nuit(float $prix_nuit): self
    {
        $this->prix_nuit = $prix_nuit;
        return $this;
    }

    #[ORM\Column(type: 'string', nullable: false)]
    private ?string $photo = null;

    public function getPhoto(): ?string
    {
        return $this->photo;
    }

    public function setPhoto(string $photo): self
    {
        $this->photo = $photo;
        return $this;
    }

    #[ORM\OneToMany(targetEntity: ReservationHebergement::class, mappedBy: 'hebergement')]
    private Collection $reservationHebergements;

    public function __construct()
    {
        $this->reservationHebergements = new ArrayCollection();
    }

    /**
     * @return Collection<int, ReservationHebergement>
     */
    public function getReservationHebergements(): Collection
    {
        if (!$this->reservationHebergements instanceof Collection) {
            $this->reservationHebergements = new ArrayCollection();
        }
        return $this->reservationHebergements;
    }

    public function addReservationHebergement(ReservationHebergement $reservationHebergement): self
    {
        if (!$this->getReservationHebergements()->contains($reservationHebergement)) {
            $this->getReservationHebergements()->add($reservationHebergement);
        }
        return $this;
    }

    public function removeReservationHebergement(ReservationHebergement $reservationHebergement): self
    {
        $this->getReservationHebergements()->removeElement($reservationHebergement);
        return $this;
    }

    public function getPrixNuit(): ?float
    {
        return $this->prix_nuit;
    }

    public function setPrixNuit(float $prix_nuit): static
    {
        $this->prix_nuit = $prix_nuit;

        return $this;
    }

}
