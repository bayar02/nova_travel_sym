<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use App\Entity\User;
use App\Repository\ReclamationRepository;

#[ORM\Entity(repositoryClass: ReclamationRepository::class)]
#[ORM\Table(name: 'reclamation')]
class Reclamation
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

    #[ORM\ManyToOne(targetEntity: User::class, inversedBy: 'reclamations')]
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

    #[ORM\Column(type: 'string', nullable: false)]
    private ?string $date_reclamation = null;

    public function getDate_reclamation(): ?string
    {
        return $this->date_reclamation;
    }

    public function setDate_reclamation(string $date_reclamation): self
    {
        $this->date_reclamation = $date_reclamation;
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
    private ?string $message = null;

    public function getMessage(): ?string
    {
        return $this->message;
    }

    public function setMessage(string $message): self
    {
        $this->message = $message;
        return $this;
    }

    #[ORM\OneToMany(targetEntity: Reponse::class, mappedBy: 'reclamation')]
    private Collection $reponses;

    public function __construct()
    {
        $this->reponses = new ArrayCollection();
    }

    /**
     * @return Collection<int, Reponse>
     */
    public function getReponses(): Collection
    {
        if (!$this->reponses instanceof Collection) {
            $this->reponses = new ArrayCollection();
        }
        return $this->reponses;
    }

    public function addReponse(Reponse $reponse): self
    {
        if (!$this->getReponses()->contains($reponse)) {
            $this->getReponses()->add($reponse);
        }
        return $this;
    }

    public function removeReponse(Reponse $reponse): self
    {
        $this->getReponses()->removeElement($reponse);
        return $this;
    }

    public function getDateReclamation(): ?string
    {
        return $this->date_reclamation;
    }

    public function setDateReclamation(string $date_reclamation): static
    {
        $this->date_reclamation = $date_reclamation;

        return $this;
    }

}
