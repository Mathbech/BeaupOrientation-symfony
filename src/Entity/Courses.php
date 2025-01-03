<?php

namespace App\Entity;

use ApiPlatform\Metadata\ApiResource;
use ApiPlatform\Metadata\Get;
use ApiPlatform\Metadata\GetCollection;
use ApiPlatform\Metadata\Post;
use ApiPlatform\Metadata\Put;
use App\Repository\CoursesRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;

#[ORM\Entity(repositoryClass: CoursesRepository::class)]
#[ORM\UniqueConstraint(name: 'UNIQ_IDENTIFIER_EMAIL', fields: ['pinCode'])]
#[UniqueEntity(fields: ['pinCode'], message: 'This code is already used')]
#[ApiResource(
    operations: [
        new Get(),
        new Post(security: "is_granted('ROLE_USER')") // Tout utilisateur peut créer sa propre entité
    ]
)]

class Courses
{

    public function __toString()
    {
        return $this->getSessionName();
    }
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $sessionName = null;

    #[ORM\Column(length: 255)]
    private ?string $pinCode = null;

    #[ORM\Column]
    private ?bool $active = null;

    #[ORM\Column]
    private ?\DateTimeImmutable $createdAt = null;

    #[ORM\Column(nullable: true)]
    private ?\DateTimeImmutable $updatedAt = null;

    #[ORM\ManyToOne(inversedBy: 'courses')]
    private ?User $user = null;

    public function __construct()
    {
        $this->createdAt = new \DateTimeImmutable();
        $this->active = true;
        $this->pinCode = substr(str_shuffle('0123456789'), 0, 4);
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getSessionName(): ?string
    {
        return $this->sessionName;
    }

    public function setSessionName(string $sessionName): static
    {
        $this->sessionName = $sessionName;

        return $this;
    }

    public function getPinCode(): ?string
    {
        return $this->pinCode;
    }

    public function setPinCode(string $pinCode): static
    {
        $this->pinCode = $pinCode;

        return $this;
    }

    public function isActive(): ?bool
    {
        return $this->active;
    }

    public function setActive(bool $active): static
    {
        $this->active = $active;

        return $this;
    }

    public function getCreatedAt(): ?\DateTimeImmutable
    {
        return $this->createdAt;
    }

    public function setCreatedAt(\DateTimeImmutable $createdAt): static
    {
        $this->createdAt = $createdAt;

        return $this;
    }

    public function getUpdatedAt(): ?\DateTimeImmutable
    {
        return $this->updatedAt;
    }

    public function setUpdatedAt(?\DateTimeImmutable $updatedAt): static
    {
        $this->updatedAt = $updatedAt;

        return $this;
    }

    public function getUser(): ?User
    {
        return $this->user;
    }

    public function setUser(?User $user): static
    {
        $this->user = $user;

        return $this;
    }
}
