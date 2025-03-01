<?php

namespace App\Entity;

use ApiPlatform\Metadata\ApiResource;
use ApiPlatform\Metadata\Get;
use ApiPlatform\Metadata\GetCollection;
use ApiPlatform\Metadata\Post;
use ApiPlatform\Metadata\Put;
use App\ApiResource\RegistrationController;
use App\Dto\RegisterRequest;
use App\Repository\UserRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Security\Core\User\PasswordAuthenticatedUserInterface;
use Symfony\Component\Security\Core\User\UserInterface;

#[ApiResource(operations: [
    new GetCollection(security: "is_granted('ROLE_ADMIN')"),
    new Post(
        uriTemplate: '/register',
        controller: RegistrationController::class,
        input: RegisterRequest::class,
        output: User::class,
        validate: true
    ),
    new Get(),
    new Put(),

]
)]


#[ORM\Entity(repositoryClass: UserRepository::class)]
#[ORM\UniqueConstraint(name: 'UNIQ_IDENTIFIER_EMAIL', fields: ['email'])]
#[UniqueEntity(fields: ['email'], message: 'There is already an account with this email')]
class User implements UserInterface, PasswordAuthenticatedUserInterface
{

    public function __toString()
    {
        return $this->getUsername();
    }
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 180)]
    private ?string $email = null;

    /**
     * @var list<string> The user roles
     */
    #[ORM\Column]
    private array $roles = [];

    /**
     * @var string The hashed password
     */
    #[ORM\Column]
    private ?string $password = null;

    #[ORM\Column(length: 255)]
    private ?string $username = null;

    #[ORM\Column]
    private ?\DateTimeImmutable $createdAt = null;

    #[ORM\Column]
    private ?\DateTimeImmutable $updatedAt = null;

    #[ORM\Column]
    private ?bool $active = null;

    #[ORM\ManyToOne(inversedBy: 'teachers')]
    private ?Schools $schools = null;

    /**
     * @var Collection<int, Markers>
     */
    #[ORM\OneToMany(targetEntity: Markers::class, mappedBy: 'teacher')]
    private Collection $markers;

    /**
     * @var Collection<int, Courses>
     */
    #[ORM\OneToMany(targetEntity: Courses::class, mappedBy: 'user')]
    private Collection $courses;

    // /**
    //  * @var Collection<int, Parcours>
    //  */
    // #[ORM\OneToMany(targetEntity: Parcours::class, mappedBy: 'user')]
    // private Collection $parcours;

    /**
     * @var Collection<int, Runners>
     */
    #[ORM\OneToMany(targetEntity: Runners::class, mappedBy: 'teacherId')]
    private Collection $runners;

    public function __construct()
    {
        $this->createdAt = new \DateTimeImmutable();
        $this->updatedAt = new \DateTimeImmutable();
        $this->active = true;
        $this->markers = new ArrayCollection();
        // $this->parcours = new ArrayCollection();
        $this->runners = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getEmail(): ?string
    {
        return $this->email;
    }

    public function setEmail(string $email): static
    {
        $this->email = $email;

        return $this;
    }

    /**
     * A visual identifier that represents this user.
     *
     * @see UserInterface
     */
    public function getUserIdentifier(): string
    {
        return (string) $this->email;
    }

    /**
     * @see UserInterface
     *
     * @return list<string>
     */
    public function getRoles(): array
    {
        $roles = $this->roles;
        // guarantee every user at least has ROLE_USER
        $roles[] = 'ROLE_USER';

        return array_unique($roles);
    }

    /**
     * @param list<string> $roles
     */
    public function setRoles(array $roles): static
    {
        $this->roles = $roles;

        return $this;
    }

    /**
     * @see PasswordAuthenticatedUserInterface
     */
    public function getPassword(): ?string
    {
        return $this->password;
    }

    public function setPassword(string $password): static
    {
        $this->password = $password;

        return $this;
    }

    /**
     * @see UserInterface
     */
    public function eraseCredentials(): void
    {
        // If you store any temporary, sensitive data on the user, clear it here
        // $this->plainPassword = null;
    }

    public function getUsername(): ?string
    {
        return $this->username;
    }

    public function setUsername(string $username): static
    {
        $this->username = $username;

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

    public function setUpdatedAt(\DateTimeImmutable $updatedAt): static
    {
        $this->updatedAt = $updatedAt;

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

    public function getSchools(): ?Schools
    {
        return $this->schools;
    }

    public function setSchools(?Schools $schools): static
    {
        $this->schools = $schools;

        return $this;
    }

    /**
     * @return Collection<int, Markers>
     */
    public function getMarkers(): Collection
    {
        return $this->markers;
    }

    public function addMarker(Markers $marker): static
    {
        if (!$this->markers->contains($marker)) {
            $this->markers->add($marker);
            $marker->setTeacher($this);
        }

        return $this;
    }

    public function removeMarker(Markers $marker): static
    {
        if ($this->markers->removeElement($marker)) {
            // set the owning side to null (unless already changed)
            if ($marker->getTeacher() === $this) {
                $marker->setTeacher(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, Courses>
     */
    public function getCourses(): Collection
    {
        return $this->courses;
    }

    public function addCourse(Courses $course): static
    {
        if (!$this->courses->contains($course)) {
            $this->courses->add($course);
            $course->setUser($this);
        }

        return $this;
    }

    public function removeCourse(Courses $course): static
    {
        if ($this->courses->removeElement($course)) {
            // set the owning side to null (unless already changed)
            if ($course->getUser() === $this) {
                $course->setUser(null);
            }
        }

        return $this;
    }

    // /**
    //  * @return Collection<int, Parcours>
    //  */
    // public function getParcours(): Collection
    // {
    //     return $this->parcours;
    // }

    // public function addParcour(Parcours $parcour): static
    // {
    //     if (!$this->parcours->contains($parcour)) {
    //         $this->parcours->add($parcour);
    //         $parcour->setUser($this);
    //     }

    //     return $this;
    // }

    // public function removeParcour(Parcours $parcour): static
    // {
    //     if ($this->parcours->removeElement($parcour)) {
    //         // set the owning side to null (unless already changed)
    //         if ($parcour->getUser() === $this) {
    //             $parcour->setUser(null);
    //         }
    //     }

    //     return $this;
    // }

    /**
     * @return Collection<int, Runners>
     */
    public function getRunners(): Collection
    {
        return $this->runners;
    }

    public function addRunner(Runners $runner): static
    {
        if (!$this->runners->contains($runner)) {
            $this->runners->add($runner);
            $runner->setTeacherId($this);
        }

        return $this;
    }

    public function removeRunner(Runners $runner): static
    {
        if ($this->runners->removeElement($runner)) {
            // set the owning side to null (unless already changed)
            if ($runner->getTeacherId() === $this) {
                $runner->setTeacherId(null);
            }
        }

        return $this;
    }
}
