<?php

// src/Service/QrCodeService.php

namespace App\Service;

use App\Entity\Markers;
use Endroid\QrCode\QrCode;
use Endroid\QrCode\Writer\PngWriter;

class QrCodeService
{
    public function __construct(private string $projectDir) {}

    public function generateQrCodeForMarker(Markers $marker): string
    {
        $data = $marker->getName();

        // ✅ Crée manuellement le QR code (v6.0.7)
        $qrCode = new QrCode($data);
        $qrCode->getSize(300);
        $qrCode->getMargin(10);

        $writer = new PngWriter();
        $result = $writer->write($qrCode);

        $filename = 'marker_' . $marker->getId() . '.png';
        $outputDir = $this->projectDir . '/public/uploads/qrcodes';

        if (!file_exists($outputDir)) {
            mkdir($outputDir, 0777, true);
        }

        $path = $outputDir . '/' . $filename;
        $result->saveToFile($path);

        return '/qrcodes/' . $filename;
    }
}
