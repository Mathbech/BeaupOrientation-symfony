<?php

namespace App\ApiResource;

use App\Entity\Runners;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;

class RunnerLoginController extends AbstractController
{
    public function __invoke(Request $request, EntityManagerInterface $entityManager): JsonResponse
    {
        // Décodage manuel du corps JSON
        $data = json_decode($request->getContent(), true);

        if (!isset($data['code']) || empty($data['code'])) {
            return new JsonResponse(['error' => 'Code is required'], JsonResponse::HTTP_BAD_REQUEST);
        }

        $code = $data['code'];

        // Recherche du runner par code
        $runner = $entityManager->getRepository(Runners::class)->findOneBy(['code' => $code]);

        if (!$runner) {
            return new JsonResponse(['error' => 'Invalid code: ' . $code], JsonResponse::HTTP_UNAUTHORIZED);
        }

        // Retour des informations du runner
        return new JsonResponse([
            'id' => $runner->getId(),
            'course' => $runner->getCourse()->getName(),
            'name' => $runner->getName(),
            'isTeacher' => $runner->isTeacher(),
            'teacherId' => $runner->getTeacherId() ? $runner->getTeacherId()->getId() : null,
        ]);
    }
}
