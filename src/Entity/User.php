<?php
namespace App\Entity;

use App\Repository\UserRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Security\Core\User\PasswordAuthenticatedUserInterface;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;

#[ORM\Entity(repositoryClass: UserRepository::class)]
#[ORM\Table(name: 'user')]
class User implements UserInterface, PasswordAuthenticatedUserInterface
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    private ?int $id = null;

    #[ORM\Column(type: 'string', length: 180, unique: true)]
    private ?string $mail = null;

    #[ORM\Column(type: 'string')]
    private ?string $password = null;

    #[ORM\Column(type: 'json')]
    private array $role = [];

    #[ORM\Column(type: 'string')]
    private ?string $nom = null;

    #[ORM\Column(type: 'string')]
    private ?string $prenom = null;

    #[ORM\Column(type: 'string')]
    private ?string $cin = null;

    #[ORM\Column(type: 'integer')]
    private ?int $tel = null;

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

    public function eraseCredentials() {}

    public function getMail(): ?string { return $this->mail; }
    public function setMail(string $mail): self { $this->mail = $mail; return $this; }

    // Add getters/setters for other fields (nom, prenom, etc.) if needed...
}
