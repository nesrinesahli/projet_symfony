<?php

namespace App\Entity;

use App\Repository\AvailabilityRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: AvailabilityRepository::class)]
class Availability
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\ManyToOne(targetEntity: Professionals::class, inversedBy: 'availability')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Professionals $professional = null;

    #[ORM\Column(type: Types::DATE_MUTABLE)]
    private ?\DateTimeInterface $available_date = null;

    #[ORM\Column(type: Types::TIME_MUTABLE)]
    private ?\DateTimeInterface $available_time = null;

    public function getId(): ?int
    {
        return $this->id;
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

    public function getAvailableDate(): ?\DateTimeInterface
    {
        return $this->available_date;
    }

    public function setAvailableDate(\DateTimeInterface $available_date): self
    {
        $this->available_date = $available_date;

        return $this;
    }

    public function getAvailableTime(): ?\DateTimeInterface
    {
        return $this->available_time;
    }

    public function setAvailableTime(\DateTimeInterface $available_time): self
    {
        $this->available_time = $available_time;

        return $this;
    }

    public function __toString(): string
    {
        return $this->available_date->format('Y-m-d') . ' at ' . $this->available_time->format('H:i:s');
    }
}
