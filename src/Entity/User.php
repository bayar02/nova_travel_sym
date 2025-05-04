<?php
namespace App\Entity;

use App\Repository\UserRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Security\Core\User\PasswordAuthenticatedUserInterface;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;

#[ORM\Entity(repositoryClass: UserRepository::class)]
#[ORM\Table(name: 'user')]
#[UniqueEntity(fields: ['mail'], message: 'There is already an account with this mail')]
class User implements UserInterface, PasswordAuthenticatedUserInterface
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    private ?int $id = null;

    #[ORM\Column(type: 'string', length: 180, unique: true)]
    private ?string $mail = null;

    #[ORM\Column(type: 'string', length: 255)]
    private ?string $password = null;

    #[ORM\Column(type: 'json')]
    private array $roles = [];

    #[ORM\Column(type: 'string')]
    private ?string $nom = null;

    #[ORM\Column(type: 'string')]
    private ?string $prenom = null;

    #[ORM\Column(type: 'string')]
    private ?string $cin = null;

    #[ORM\Column(type: 'string')]
    private ?string $tel = null;

    #[ORM\Column]
    private bool $isVerified = false;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $reset_token = null;

    private ?string $plainPassword = null;

    #[ORM\OneToMany(mappedBy: 'user', targetEntity: Reclamation::class, orphanRemoval: true)]
    private Collection $reclamations;

    #[ORM\OneToMany(mappedBy: 'user', targetEntity: ReservationHebergement::class, orphanRemoval: true)]
    private Collection $reservationHebergements;

    #[ORM\OneToMany(mappedBy: 'user', targetEntity: ReservationVol::class, orphanRemoval: true)]
    private Collection $reservationVols;

    // Constructor + relations (same as yours)
    public function __construct()
    {
        $this->reclamations = new ArrayCollection();
        $this->reservationHebergements = new ArrayCollection();
        $this->reservationVols = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getUserIdentifier(): string
    {
        return (string) $this->mail;
    }

    public function getUsername(): string
    {
        return $this->getUserIdentifier(); // Legacy compatibility
    }

    public function getRoles(): array
    {
        $roles = $this->roles;
        // guarantee every user at least has ROLE_USER
        $roles[] = 'ROLE_USER';
        return array_unique($roles);
    }

    public function setRoles(array $roles): self
    {
        $this->roles = $roles;
        return $this;
    }

    public function getPassword(): string
    {
        return $this->password;
    }

    public function setPassword(string $password): self
    {
        $this->password = $password;
        return $this;
    }

    public function eraseCredentials(): void
    {
        // If you store any temporary, sensitive data on the user, clear it here
        // $this->plainPassword = null;
    }

    public function getMail(): ?string { return $this->mail; }
    public function setMail(string $mail): self { $this->mail = $mail; return $this; }

    public function getNom(): ?string
    {
        return $this->nom;
    }

    public function setNom(string $nom): self
    {
        $this->nom = $nom;
        return $this;
    }

    public function getPrenom(): ?string
    {
        return $this->prenom;
    }

    public function setPrenom(string $prenom): self
    {
        $this->prenom = $prenom;
        return $this;
    }

    public function getCin(): ?string
    {
        return $this->cin;
    }

    public function setCin(string $cin): self
    {
        $this->cin = $cin;
        return $this;
    }

    public function getTel(): ?string
    {
        return $this->tel;
    }

    public function setTel(string $tel): self
    {
        $this->tel = $tel;
        return $this;
    }

    public function isVerified(): bool
    {
        return $this->isVerified;
    }

    public function setIsVerified(bool $isVerified): static
    {
        $this->isVerified = $isVerified;

        return $this;
    }

    public function getResetToken(): ?string
    {
        return $this->reset_token;
    }

    public function setResetToken(?string $reset_token): static
    {
        $this->reset_token = $reset_token;
        return $this;
    }

    public function getPlainPassword(): ?string
    {
        return $this->plainPassword;
    }

    public function setPlainPassword(?string $plainPassword): self
    {
        $this->plainPassword = $plainPassword;
        return $this;
    }

    /**
     * @return Collection<int, Reclamation>
     */
    public function getReclamations(): Collection
    {
        return $this->reclamations;
    }

    public function addReclamation(Reclamation $reclamation): static
    {
        if (!$this->reclamations->contains($reclamation)) {
            $this->reclamations->add($reclamation);
            $reclamation->setUser($this);
        }

        return $this;
    }

    public function removeReclamation(Reclamation $reclamation): static
    {
        if ($this->reclamations->removeElement($reclamation)) {
            // set the owning side to null (unless already changed)
            if ($reclamation->getUser() === $this) {
                $reclamation->setUser(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, ReservationHebergement>
     */
    public function getReservationHebergements(): Collection
    {
        return $this->reservationHebergements;
    }

    public function addReservationHebergement(ReservationHebergement $reservationHebergement): static
    {
        if (!$this->reservationHebergements->contains($reservationHebergement)) {
            $this->reservationHebergements->add($reservationHebergement);
            $reservationHebergement->setUser($this);
        }

        return $this;
    }

    public function removeReservationHebergement(ReservationHebergement $reservationHebergement): static
    {
        if ($this->reservationHebergements->removeElement($reservationHebergement)) {
            // set the owning side to null (unless already changed)
            if ($reservationHebergement->getUser() === $this) {
                $reservationHebergement->setUser(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, ReservationVol>
     */
    public function getReservationVols(): Collection
    {
        return $this->reservationVols;
    }

    public function addReservationVol(ReservationVol $reservationVol): static
    {
        if (!$this->reservationVols->contains($reservationVol)) {
            $this->reservationVols->add($reservationVol);
            $reservationVol->setUser($this);
        }

        return $this;
    }

    public function removeReservationVol(ReservationVol $reservationVol): static
    {
        if ($this->reservationVols->removeElement($reservationVol)) {
            // set the owning side to null (unless already changed)
            if ($reservationVol->getUser() === $this) {
                $reservationVol->setUser(null);
            }
        }

        return $this;
    }
}
