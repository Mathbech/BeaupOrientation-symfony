<?php

namespace App\ApiResource;

use App\Entity\Runners;
use App\Entity\Markers;
use App\Entity\LogScan;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;

class ScanController extends AbstractController
{
    public function __invoke(Request $request, EntityManagerInterface $em): JsonResponse
    {
        $data = json_decode($request->getContent(), true);

        $runnerId = $data['runner_id'] ?? null;
        $markerCode = $data['marker_code'] ?? null; // ex: "BALISE-12"

        if (!$runnerId || !$markerCode) {
            return new JsonResponse(['error' => 'runner_id and marker_code are required'], JsonResponse::HTTP_BAD_REQUEST);
        }

        // Extraire l'ID depuis "BALISE-{id}"
        if (!preg_match('/^BALISE-(\d+)$/', $markerCode, $matches)) {
            return new JsonResponse(['error' => 'Invalid marker_code format'], JsonResponse::HTTP_BAD_REQUEST);
        }
        $markerId = (int)$matches[1];

        $runner = $em->getRepository(Runners::class)->find($runnerId);
        $marker = $em->getRepository(Markers::class)->find($markerId);

        if (!$runner || !$marker) {
            return new JsonResponse(['error' => 'Invalid runner or marker'], JsonResponse::HTTP_BAD_REQUEST);
        }

        // Vérifier que le marker appartient à la même course que le runner
        if (
            $runner->getCourse()->getId() !== $marker->getCourses()->getId()
        ) {
            return new JsonResponse(
                ['status' => 'invalid', 'message' => 'Cette balise ne correspond pas à la course du coureur'],
                422 // Unprocessable Entity
            );
        }

        // Récupérer la position du scan
        $scanLat = null;
        $scanLng = null;
        if (isset($data['point']) && is_array($data['point'])) {
            // Si point est un GeoJSON-like avec 'coordinates'
            if (isset($data['point']['coordinates']) && is_array($data['point']['coordinates']) && count($data['point']['coordinates']) === 2) {
                $scanLng = $data['point']['coordinates'][0];
                $scanLat = $data['point']['coordinates'][1];
            }
            // (optionnel) compatibilité avec l'ancien format
            elseif (isset($data['point']['lat'], $data['point']['lng'])) {
                $scanLat = $data['point']['lat'];
                $scanLng = $data['point']['lng'];
            }
        }

        if ($scanLat === null || $scanLng === null) {
            return new JsonResponse(['error' => 'latitude and longitude are required'], JsonResponse::HTTP_BAD_REQUEST);
        }

        // Récupérer la position du marker
        $markerLat = $marker->getPoint()->getY(); // latitude
        $markerLng = $marker->getPoint()->getX(); // longitude

        // Calculer la distance (en mètres)
        $distance = $this->haversineDistance($scanLat, $scanLng, $markerLat, $markerLng);
        // Vérifie si déjà scanné
        $existing = $em->getRepository(LogScan::class)->findOneBy([
            'runner' => $runner,
            'marker' => $marker,
        ]);

        if ($existing) {
            return new JsonResponse(
                ['status' => 'invalid', 'message' => 'Déjà scannée'],
                422 // Unprocessable Entity
            );
        }

        // Création du log (toujours, même si trop loin)
        $log = new LogScan();
        $log->setRunner($runner);
        $log->setMarker($marker);
        $log->setPoint(new \LongitudeOne\Spatial\PHP\Types\Geometry\Point($scanLng, $scanLat)); // longitude, latitude
        $log->setStatut($distance <= $_ENV['DISTANCE_SCAN'] ? 1 : 0);

        $em->persist($log);
        $em->flush();

        if ($distance > $_ENV['DISTANCE_SCAN']) {
            return new JsonResponse(
                ['status' => 'invalid', 'message' => 'Trop loin du marker (distance: ' . round($distance, 2) . ' m)'],
                422 // Unprocessable Entity
            );
        }

        return new JsonResponse(
            ['status' => 'valid', 'message' => 'Scan validé'],
            200
        );
    }

    /**
     * Calcule la distance entre deux points GPS en mètres (formule de Haversine)
     */
    private function haversineDistance(
        float $scanLat,
        float $scanLng,
        float $markerLat,
        float $markerLng
    ): float {
        $earthRadius = 6371000; // mètres

        // Conversion des degrés en radians
        $scanLatRad = deg2rad($scanLat);
        $markerLatRad = deg2rad($markerLat);
        $deltaLat = $markerLatRad - $scanLatRad;
        $deltaLng = deg2rad($markerLng - $scanLng);

        // Formule de Haversine
        $a = sin($deltaLat / 2) ** 2 +
            cos($scanLatRad) * cos($markerLatRad) *
            sin($deltaLng / 2) ** 2;
        $c = 2 * atan2(sqrt($a), sqrt(1 - $a));
        return $earthRadius * $c;
    }
}
