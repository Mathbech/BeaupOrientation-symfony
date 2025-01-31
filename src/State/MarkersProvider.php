<?php

namespace App\State;

use ApiPlatform\State\ProviderInterface;
use Doctrine\ORM\EntityManagerInterface;
use App\Entity\Markers;
use App\Entity\Runners;

class MarkersProvider implements ProviderInterface
{
    public function __construct(
        private EntityManagerInterface $entityManager
    ) {}

    public function provide(\ApiPlatform\Metadata\Operation $operation, array $uriVariables = [], array $context = []): array
    {
        // 🔍 Vérifier si le runnerId est passé en paramètre
        $runnerId = $context['request']->query->get('runnerId');

        if (!$runnerId) {
            return []; // ❌ Aucun runnerId fourni → Liste vide
        }

        // 🔍 Récupérer le Runner en base
        $runner = $this->entityManager->getRepository(Runners::class)->find($runnerId);

        if (!$runner) {
            return []; // ❌ Aucun Runner trouvé avec cet ID
        }

        // 🔍 Récupérer le teacherId lié
        $teacher = $runner->getTeacherId();

        if (!$teacher) {
            return []; // ❌ Aucun teacherId → Liste vide
        }

        // ✅ Filtrer les markers appartenant à cet User (teacher)
        return $this->entityManager->getRepository(Markers::class)
            ->findBy(['teacher' => $teacher]);
    }
}
