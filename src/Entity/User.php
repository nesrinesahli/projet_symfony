<?php
// src/Entity/User.php
namespace App\Entity;

use App\Repository\UserRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Security\Core\User\PasswordAuthenticatedUserInterface;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Entity(repositoryClass: UserRepository::class)]
class User implements UserInterface, PasswordAuthenticatedUserInterface
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\OneToOne(mappedBy: 'user', targetEntity: Patient::class, cascade: ['persist', 'remove'])]
    private ?Patient $patient = null;
    #[ORM\OneToOne(targetEntity: Professionals::class, mappedBy: 'user', cascade: ['persist', 'remove'])]
    private ?Professionals $professional = null;

    #[ORM\Column(length: 100, unique: true)]
    #[Assert\NotBlank(message: "Email is required.")]
    #[Assert\Email(message: "Invalid email address.")]
    private ?string $email = null;

    #[ORM\Column(length: 255)]
    #[Assert\NotBlank(message: "Password is required.")]
    private ?string $password = null;

    #[ORM\Column(length: 20)]
    #[Assert\NotBlank(message: "Role is required.")]
    private ?string $role = null;

    public function getId(): ?int
    {
        return $this->id;
    }
    public function getPatient(): ?Patient
    {
        return $this->patient;
    }

    public function setPatient(?Patient $patient): self
    {
        $this->patient = $patient;

        // Ensure the inverse side is updated
        if ($patient && $patient->getUser() !== $this) {
            $patient->setUser($this);
        }

        return $this;
    }

    public function getProfessional(): ?Professionals
    {
        return $this->professional;
    }
    public function setProfessional(?Professionals $professional): self
    {
        $this->professional = $professional;

        return $this;
    }

    public function getEmail(): ?string
    {
        return $this->email;
    }

    public function setEmail(string $email): self
    {
        $this->email = $email;
        return $this;
    }

    public function getPassword(): ?string
    {
        return $this->password;
    }

    public function setPassword(string $password): self
    {
        $this->password = $password;
        return $this;
    }

    public function getRole(): ?string
    {
        return $this->role;
    }

    public function setRole(string $role): self
    {
        $this->role = $role;
        return $this;
    }

    // Implementing UserInterface methods
    public function getUserIdentifier(): string
    {
        return $this->email; // Using email as the identifier
    }

    public function getRoles(): array
    {
        return [$this->role]; // Return roles as an array
    }

    public function eraseCredentials(): void
    {
        // Clear sensitive temporary data if any
    }

    // __toString() is useful for debugging, not required for the user interface
    public function __toString(): string
    {
        return $this->email;
    }
}
