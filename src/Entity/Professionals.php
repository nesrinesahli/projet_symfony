<?php

namespace App\Entity;

use App\Repository\ProfessionalsRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Entity(repositoryClass: ProfessionalsRepository::class)]
class Professionals
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\OneToOne(inversedBy: 'professional', targetEntity: User::class, cascade: ['persist', 'remove'])]
    private ?User $user = null;

    #[ORM\Column(length: 50)]
    #[Assert\NotBlank(message: "First name is required.")]
    private ?string $first_name = null;

    #[ORM\Column(length: 50)]
    #[Assert\NotBlank(message: "Last name is required.")]
    private ?string $last_name = null;

    #[ORM\Column(length: 20, unique: true)]
    #[Assert\NotBlank(message: "CIN card number is required.")]
    private ?string $cin_card_number = null;

    #[ORM\Column(length: 100)]
    #[Assert\NotBlank(message: "Specialty is required.")]
    private ?string $specialty = null;

    #[ORM\Column(length: 20)]
    #[Assert\NotBlank(message: "Phone number is required.")]
    private ?string $phone_number = null;

    #[ORM\Column(type: Types::TEXT, nullable: true)]
    private ?string $description = null;

    #[ORM\Column(type: Types::DATETIME_MUTABLE)]
    private ?\DateTimeInterface $created_at = null;

    #[ORM\Column(type: Types::TEXT)]
    private ?string $address = null;

    #[ORM\OneToMany(mappedBy: 'professional', targetEntity: Availability::class, cascade: ['persist', 'remove'])]
    private Collection $availability;

    #[ORM\OneToMany(mappedBy: 'professional', targetEntity: Rendezvous::class, cascade: ['persist', 'remove'])]
    private Collection $rendezvous;

    public function __construct()
    {
        $this->created_at = new \DateTime();
        $this->availability = new ArrayCollection();
        $this->rendezvous = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getUser(): ?User
    {
        return $this->user;
    }

    public function setUser(User $user): self
    {
        $this->user = $user;

        return $this;
    }

    public function getFirstName(): ?string
    {
        return $this->first_name;
    }

    public function setFirstName(string $first_name): self
    {
        $this->first_name = $first_name;

        return $this;
    }

    public function getLastName(): ?string
    {
        return $this->last_name;
    }

    public function setLastName(string $last_name): self
    {
        $this->last_name = $last_name;

        return $this;
    }

    public function getCinCardNumber(): ?string
    {
        return $this->cin_card_number;
    }

    public function setCinCardNumber(string $cin_card_number): self
    {
        $this->cin_card_number = $cin_card_number;

        return $this;
    }

    public function getSpecialty(): ?string
    {
        return $this->specialty;
    }

    public function setSpecialty(string $specialty): self
    {
        $this->specialty = $specialty;

        return $this;
    }

    public function getPhoneNumber(): ?string
    {
        return $this->phone_number;
    }

    public function setPhoneNumber(string $phone_number): self
    {
        $this->phone_number = $phone_number;

        return $this;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(?string $description): self
    {
        $this->description = $description;

        return $this;
    }

    public function getCreatedAt(): ?\DateTimeInterface
    {
        return $this->created_at;
    }

    public function getAddress(): ?string
    {
        return $this->address;
    }

    public function setAddress(string $address): self
    {
        $this->address = $address;

        return $this;
    }

    public function getAvailability(): Collection
    {
        return $this->availability;
    }

    public function addAvailability(Availability $availability): self
    {
        if (!$this->availability->contains($availability)) {
            $this->availability[] = $availability;
            $availability->setProfessional($this);
        }

        return $this;
    }

    public function removeAvailability(Availability $availability): self
    {
        if ($this->availability->removeElement($availability)) {
            if ($availability->getProfessional() === $this) {
                $availability->setProfessional(null);
            }
        }

        return $this;
    }

    public function getRendezvous(): Collection
    {
        return $this->rendezvous;
    }

    public function addRendezvous(Rendezvous $rendezvous): self
    {
        if (!$this->rendezvous->contains($rendezvous)) {
            $this->rendezvous[] = $rendezvous;
            $rendezvous->setProfessional($this);
        }

        return $this;
    }

    public function removeRendezvous(Rendezvous $rendezvous): self
    {
        if ($this->rendezvous->removeElement($rendezvous)) {
            if ($rendezvous->getProfessional() === $this) {
                $rendezvous->setProfessional(null);
            }
        }

        return $this;
    }

    public function __toString(): string
    {
        return $this->first_name . ' ' . $this->last_name;
    }
}
