<?php

namespace App\Entity;

use ApiPlatform\Metadata\ApiResource;
use App\Repository\CoursesRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: CoursesRepository::class)]
#[ApiResource]
class Courses
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $name = null;

    #[ORM\ManyToOne(inversedBy: 'courses')]
    private ?Parcours $parcours = null;

    #[ORM\ManyToOne(inversedBy: 'courses')]
    private ?User $user = null;

    /**
     * @var Collection<int, Runners>
     */
    #[ORM\OneToMany(targetEntity: Runners::class, mappedBy: 'course')]
    private Collection $runners;

    public function __construct()
    {
        $this->runners = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name): static
    {
        $this->name = $name;

        return $this;
    }

    public function getParcours(): ?Parcours
    {
        return $this->parcours;
    }

    public function setParcours(?Parcours $parcours): static
    {
        $this->parcours = $parcours;

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
            $runner->setCourse($this);
        }

        return $this;
    }

    public function removeRunner(Runners $runner): static
    {
        if ($this->runners->removeElement($runner)) {
            // set the owning side to null (unless already changed)
            if ($runner->getCourse() === $this) {
                $runner->setCourse(null);
            }
        }

        return $this;
    }
}
