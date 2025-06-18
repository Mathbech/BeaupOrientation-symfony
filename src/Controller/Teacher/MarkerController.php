<?php

// src/Controller/MarkerController.php

namespace App\Controller\Teacher;

use App\Entity\Markers;
use App\Service\QrCodeService;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;

class MarkerController extends AbstractController
{
    #[Route('/markers/{id}/generate-qr', name: 'marker_generate_qr')]
    public function generateQr(
        Markers $marker,
        QrCodeService $qrCodeService,
        EntityManagerInterface $em,
        Request $request // Injection de l'objet Request
    ) {
        // Vérifier que le marker appartient à l'utilisateur connecté
        $user = $this->getUser();
        if ($marker->getTeacher() !== $user) {
            $this->addFlash('error', 'Vous n\'êtes pas autorisé à générer un QR Code pour ce marker.');
            return $this->redirectToRoute('teacher_home');
        }

        // Générer le QR Code
        $qrPath = $qrCodeService->generateQrCodeForMarker($marker);
        $marker->setQrCode($qrPath);
        $em->flush();

        $this->addFlash('success', 'QR Code généré avec succès.');

        // Rediriger vers la page précédente ou vers une route par défaut
        $referer = $request->headers->get('referer'); // Utilisation de l'objet Request
        if ($referer) {
            return $this->redirect($referer);
        }

        return $this->redirectToRoute('teacher_home');
    }
}
