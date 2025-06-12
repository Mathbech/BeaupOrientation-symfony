<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use LongitudeOne\Spatial\PHP\Types\SpatialInterface;
use ApiPlatform\Metadata\ApiResource;
use ApiPlatform\Metadata\Post;
use App\ApiResource\ScanController;

#[ApiResource(
    operations: [
        new Post(
            uriTemplate: '/logscan/scan',
            controller: ScanController::class,
            // input: RunnerLoginInput::class,
            output: false,
        ),
    ]
)]

#[ORM\Entity]
class LogScan
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\ManyToOne(targetEntity: Runners::class)]
    private ?Runners $runner = null;

    #[ORM\ManyToOne(targetEntity: Markers::class)]
    private ?Markers $marker = null;

    #[ORM\Column(type: 'datetime')]
    private \DateTimeInterface $scannedAt;

    #[ORM\Column(type: 'geometry_point', nullable: true)]
    private ?SpatialInterface $point = null;

    #[ORM\Column(nullable: true)]
    private ?int $statut = null;

    public function __construct()
    {
        $this->scannedAt = new \DateTime();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function setId(?int $id): self
    {
        $this->id = $id;
        return $this;
    }

    public function getRunner(): ?Runners
    {
        return $this->runner;
    }

    public function setRunner(?Runners $runner): self
    {
        $this->runner = $runner;
        return $this;
    }

    public function getMarker(): ?Markers
    {
        return $this->marker;
    }

    public function setMarker(?Markers $marker): self
    {
        $this->marker = $marker;
        return $this;
    }

    public function getScannedAt(): \DateTimeInterface
    {
        return $this->scannedAt;
    }

    public function setScannedAt(\DateTimeInterface $scannedAt): self
    {
        $this->scannedAt = $scannedAt;
        return $this;
    }

    public function getPoint(): ?SpatialInterface
    {
        return $this->point;
    }

    public function setPoint(?SpatialInterface $point): static
    {
        if ($point !== null) {
            $point->setSrid(4326); // ✅ Définir le SRID à 4326
        }

        $this->point = $point;

        return $this;
    }

    public function __toString(): string
    {
        return sprintf(
            'Scan #%d: %s at %s (GPS: %s)',
            $this->id,
            $this->runner ? $this->runner->getName() : 'Unknown Runner',
            $this->scannedAt->format('Y-m-d H:i:s'),
            $this->point ?? 'N/A'
        );
    }

    public function getStatut(): ?int
    {
        return $this->statut;
    }

    public function setStatut(?int $statut): static
    {
        $this->statut = $statut;

        return $this;
    }
}
