<?php

namespace App\Entity;

use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;

use App\Repository\EventRepository;

#[ORM\Entity(repositoryClass: EventRepository::class)]
#[ORM\Table(name: 'event')]
class Event
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

    #[ORM\Column(type: 'text', nullable: false)]
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

    #[ORM\Column(type: 'string', nullable: false)]
    private ?string $lieu = null;

    public function getLieu(): ?string
    {
        return $this->lieu;
    }

    public function setLieu(string $lieu): self
    {
        $this->lieu = $lieu;
        return $this;
    }

    #[ORM\Column(type: 'string', nullable: false)]
    private ?string $date_event = null;

    public function getDate_event(): ?string
    {
        return $this->date_event;
    }

    public function setDate_event(string $date_event): self
    {
        $this->date_event = $date_event;
        return $this;
    }

    #[ORM\Column(type: 'integer', nullable: false)]
    private ?int $duree = null;

    public function getDuree(): ?int
    {
        return $this->duree;
    }

    public function setDuree(int $duree): self
    {
        $this->duree = $duree;
        return $this;
    }

    #[ORM\Column(type: 'decimal', nullable: false)]
    private ?float $prix = null;

    public function getPrix(): ?float
    {
        return $this->prix;
    }

    public function setPrix(float $prix): self
    {
        $this->prix = $prix;
        return $this;
    }

    #[ORM\ManyToMany(targetEntity: Planning::class, inversedBy: 'events')]
    #[ORM\JoinTable(
        name: 'planning_events',
        joinColumns: [
            new ORM\JoinColumn(name: 'id_event', referencedColumnName: 'id')
        ],
        inverseJoinColumns: [
            new ORM\JoinColumn(name: 'id_planning', referencedColumnName: 'id')
        ]
    )]
    private Collection $plannings;

    public function __construct()
    {
        $this->plannings = new ArrayCollection();
    }

    /**
     * @return Collection<int, Planning>
     */
    public function getPlannings(): Collection
    {
        if (!$this->plannings instanceof Collection) {
            $this->plannings = new ArrayCollection();
        }
        return $this->plannings;
    }

    public function addPlanning(Planning $planning): self
    {
        if (!$this->getPlannings()->contains($planning)) {
            $this->getPlannings()->add($planning);
        }
        return $this;
    }

    public function removePlanning(Planning $planning): self
    {
        $this->getPlannings()->removeElement($planning);
        return $this;
    }

    public function getDateEvent(): ?string
    {
        return $this->date_event;
    }

    public function setDateEvent(string $date_event): static
    {
        $this->date_event = $date_event;

        return $this;
    }

}
