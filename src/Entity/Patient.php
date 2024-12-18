<?php

namespace App\Entity;

use App\Repository\PatientRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Entity(repositoryClass: PatientRepository::class)]
class Patient
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\OneToOne(inversedBy: 'patient', targetEntity: User::class, cascade: ['persist', 'remove'])]
    #[ORM\JoinColumn(nullable: false)]
    private $user;

    #[ORM\Column(length: 50)]
    #[Assert\NotBlank(message: "First name is required.")]
    private ?string $first_name = null;

    #[ORM\Column(length: 50)]
    #[Assert\NotBlank(message: "Last name is required.")]
    private ?string $last_name = null;

    #[ORM\Column(length: 10, nullable: true)]
    private ?string $gender = null;

    #[ORM\Column(type: Types::DATE_MUTABLE, nullable: true)]
    private ?\DateTimeInterface $date_of_birth = null;

    #[ORM\Column(nullable: true)]
    private ?bool $chronic_disease = null;

    #[ORM\Column(length: 20)]
    #[Assert\NotBlank(message: "Phone number is required.")]
    private ?string $phone_number = null;

    #[ORM\Column(type: Types::TEXT, nullable: true)]
    private ?string $address = null;

    #[ORM\Column(type: Types::DATETIME_MUTABLE)]
    private ?\DateTimeInterface $created_at = null;

    // Inverse side for Rendezvous
    #[ORM\OneToMany(mappedBy: 'patient', targetEntity: Rendezvous::class)]
    private Collection $rendezvous;

    public function __construct()
    {
        $this->created_at = new \DateTime();
        $this->rendezvous = new ArrayCollection();
        $this->user = null;
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

        // Ensure the inverse side is updated
        if ($user->getPatient() !== $this) {
            $user->setPatient($this);
        }

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
    public function getFullName(): string
    {
        return $this->first_name . ' ' . $this->last_name;
    }

    public function getGender(): ?string
    {
        return $this->gender;
    }

    public function setGender(?string $gender): self
    {
        $this->gender = $gender;

        return $this;
    }

    public function getDateOfBirth(): ?\DateTimeInterface
    {
        return $this->date_of_birth;
    }

    public function setDateOfBirth(?\DateTimeInterface $date_of_birth): self
    {
        $this->date_of_birth = $date_of_birth;

        return $this;
    }

    public function getChronicDisease(): ?bool
    {
        return $this->chronic_disease;
    }

    public function setChronicDisease(?bool $chronic_disease): self
    {
        $this->chronic_disease = $chronic_disease;

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

    public function getAddress(): ?string
    {
        return $this->address;
    }

    public function setAddress(?string $address): self
    {
        $this->address = $address;

        return $this;
    }

    public function getCreatedAt(): ?\DateTimeInterface
    {
        return $this->created_at;
    }

    public function setCreatedAt(\DateTimeInterface $createdAt): self
    {
        $this->created_at = $createdAt;

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
            $rendezvous->setPatient($this);
        }

        return $this;
    }

    public function removeRendezvous(Rendezvous $rendezvous): self
    {
        if ($this->rendezvous->removeElement($rendezvous)) {
            if ($rendezvous->getPatient() === $this) {
                $rendezvous->setPatient(null);
            }
        }

        return $this;
    }
}
