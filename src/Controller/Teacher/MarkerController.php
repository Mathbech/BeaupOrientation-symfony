<?php

// src/Controller/MarkerController.php

namespace App\Controller\Teacher;

use App\Entity\Markers;
use App\Repository\MarkersRepository;
use App\Service\QrCodeService;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class MarkerController extends AbstractController
{
    #[Route('/markers/{id}/generate-qr', name: 'marker_generate_qr')]
    public function generateQr(
        Markers $marker,
        QrCodeService $qrCodeService,
        EntityManagerInterface $em,
        Request $request
    ): RedirectResponse {
        $qrPath = $qrCodeService->generateQrCodeForMarker($marker);
        $marker->setQrCode($qrPath);
        $em->flush();

        $this->addFlash('success', 'QR Code généré avec succès.');
        return $this->redirect($request->server->get('HTTP_REFERER'));
    }
}
