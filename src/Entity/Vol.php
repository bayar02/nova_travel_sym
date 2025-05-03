<?php

namespace App\Entity;

use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Calendar\Calendar;
use CalendarBundle\Entity\Event;
use CalendarBundle\Event\CalendarEvent;

use App\Repository\VolRepository;

#[ORM\Entity(repositoryClass: VolRepository::class)]
#[ORM\Table(name: 'vol')]
class Vol implements \JsonSerializable
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
    private ?string $compagnie = null;

    public function getCompagnie(): ?string
    {
        return $this->compagnie;
    }

    public function setCompagnie(string $compagnie): self
    {
        $this->compagnie = $compagnie;
        return $this;
    }

    #[ORM\Column(type: 'string', nullable: false)]
    private ?string $aeroport_depart = null;

    public function getAeroport_depart(): ?string
    {
        return $this->aeroport_depart;
    }

    public function setAeroport_depart(string $aeroport_depart): self
    {
        $this->aeroport_depart = $aeroport_depart;
        return $this;
    }

    #[ORM\Column(type: 'string', nullable: false)]
    private ?string $aeroport_arrivee = null;

    public function getAeroport_arrivee(): ?string
    {
        return $this->aeroport_arrivee;
    }

    public function setAeroport_arrivee(string $aeroport_arrivee): self
    {
        $this->aeroport_arrivee = $aeroport_arrivee;
        return $this;
    }

    #[ORM\Column(type: 'date', nullable: false)]
    private ?\DateTimeInterface $date_depart = null;

    public function getDate_depart(): ?\DateTimeInterface
    {
        return $this->date_depart;
    }

    public function setDate_depart(\DateTimeInterface $date_depart): self
    {
        $this->date_depart = $date_depart;
        return $this;
    }

    #[ORM\Column(type: 'date', nullable: false)]
    private ?\DateTimeInterface $date_arrivee = null;

    public function getDate_arrivee(): ?\DateTimeInterface
    {
        return $this->date_arrivee;
    }

    public function setDate_arrivee(\DateTimeInterface $date_arrivee): self
    {
        $this->date_arrivee = $date_arrivee;
        return $this;
    }

    #[ORM\Column(type: 'float', nullable: false)]
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

    #[ORM\Column(type: 'string', nullable: false)]
    private ?string $destination = null;

    public function getDestination(): ?string
    {
        return $this->destination;
    }

    public function setDestination(string $destination): self
    {
        $this->destination = $destination;
        return $this;
    }

    #[ORM\OneToMany(targetEntity: ReservationVol::class, mappedBy: 'vol')]
    private Collection $reservationVols;

    public function __construct()
    {
        $this->reservationVols = new ArrayCollection();
    }

    /**
     * @return Collection<int, ReservationVol>
     */
    public function getReservationVols(): Collection
    {
        if (!$this->reservationVols instanceof Collection) {
            $this->reservationVols = new ArrayCollection();
        }
        return $this->reservationVols;
    }

    public function addReservationVol(ReservationVol $reservationVol): self
    {
        if (!$this->getReservationVols()->contains($reservationVol)) {
            $this->getReservationVols()->add($reservationVol);
        }
        return $this;
    }

    public function removeReservationVol(ReservationVol $reservationVol): self
    {
        $this->getReservationVols()->removeElement($reservationVol);
        return $this;
    }

    public function getAeroportDepart(): ?string
    {
        return $this->aeroport_depart;
    }

    public function setAeroportDepart(string $aeroport_depart): static
    {
        $this->aeroport_depart = $aeroport_depart;

        return $this;
    }

    public function getAeroportArrivee(): ?string
    {
        return $this->aeroport_arrivee;
    }

    public function setAeroportArrivee(string $aeroport_arrivee): static
    {
        $this->aeroport_arrivee = $aeroport_arrivee;

        return $this;
    }

    public function getDateDepart(): ?\DateTimeInterface
    {
        return $this->date_depart;
    }

    public function setDateDepart(\DateTimeInterface $date_depart): static
    {
        $this->date_depart = $date_depart;

        return $this;
    }

    public function getDateArrivee(): ?\DateTimeInterface
    {
        return $this->date_arrivee;
    }

    public function setDateArrivee(\DateTimeInterface $date_arrivee): static
    {
        $this->date_arrivee = $date_arrivee;

        return $this;
    }

    public function jsonSerialize(): array
    {
        return [
            'id' => $this->id,
            'title' => $this->compagnie . ' - ' . $this->destination,
            'start' => $this->date_depart->format('Y-m-d H:i:s'),
            'end' => $this->date_arrivee->format('Y-m-d H:i:s'),
            'description' => sprintf(
                'From: %s\nTo: %s\nPrice: %s€',
                $this->aeroport_depart,
                $this->aeroport_arrivee,
                $this->prix
            ),
            'backgroundColor' => '#4e73df',
            'borderColor' => '#4e73df',
            'textColor' => '#fff',
            'url' => '/admin/vol/' . $this->id,
        ];
    }

    public function toEvent(): array
    {
        return [
            'title' => $this->compagnie . ' - ' . $this->destination,
            'start' => $this->date_depart,
            'end' => $this->date_arrivee,
            'description' => sprintf(
                'From: %s\nTo: %s\nPrice: %s€',
                $this->aeroport_depart,
                $this->aeroport_arrivee,
                $this->prix
            ),
            'backgroundColor' => '#4e73df',
            'borderColor' => '#4e73df',
            'textColor' => '#fff',
            'url' => '/admin/vol/' . $this->id,
        ];
    }
}
