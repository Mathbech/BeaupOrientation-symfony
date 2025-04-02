<?php

namespace App\State;

use ApiPlatform\State\ProviderInterface;
use App\Entity\Courses;
use Doctrine\ORM\EntityManagerInterface;
use App\Entity\Markers;

class MarkersProvider implements ProviderInterface
{
    public function __construct(
        private EntityManagerInterface $entityManager
    ) {}

    public function provide(\ApiPlatform\Metadata\Operation $operation, array $uriVariables = [], array $context = []): array
    {
        // 🔍 Vérifier si le courseId est passé en paramètre
        $courseId = $context['request']->query->get('courseId');

        if (!$courseId) {
            return []; // ❌ Aucun courseId fourni → Liste vide
        }

        // 🔍 Récupérer la course en base
        $course = $this->entityManager->getRepository(Courses::class)->find($courseId);

        if (!$course) {
            return []; // ❌ Aucun course trouvé avec cet ID
        }

        // ✅ Filtrer les markers appartenant à cette course
        return $this->entityManager->getRepository(Markers::class)
            ->findBy(['courses' => $course]);
    }
}
