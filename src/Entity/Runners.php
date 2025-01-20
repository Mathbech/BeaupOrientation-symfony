<?php

namespace App\Entity;

use ApiPlatform\Metadata\ApiResource;
use ApiPlatform\Metadata\Get;
use App\Repository\RunnersRepository;
use ApiPlatform\Metadata\Post;
use App\Dto\RunnerLoginInput;
use App\ApiResource\RunnerLoginController;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: RunnersRepository::class)]
#[ApiResource(
    operations: [
        new Post(
            uriTemplate: '/runners/login',
            controller: RunnerLoginController::class,
            input: RunnerLoginInput::class,
            output: false,
        ),
        new Get(
            uriTemplate: '/runners/{id}',
        ),
    ]
)]
class Runners
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $name = null;

    #[ORM\ManyToOne(inversedBy: 'runners')]
    private ?Courses $course = null;

    #[ORM\Column(length: 255)]
    private ?string $code = null;

    #[ORM\Column]
    private ?bool $isTeacher = null;

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

    public function getCourse(): ?Courses
    {
        return $this->course;
    }

    public function setCourse(?Courses $course): static
    {
        $this->course = $course;

        return $this;
    }

    public function getCode(): ?string
    {
        return $this->code;
    }

    public function setCode(string $code): static
    {
        $this->code = $code;

        return $this;
    }

    public function isTeacher(): ?bool
    {
        return $this->isTeacher;
    }

    public function setTeacher(bool $isTeacher): static
    {
        $this->isTeacher = $isTeacher;

        return $this;
    }
}
