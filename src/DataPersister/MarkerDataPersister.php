<?php

namespace App\DataPersister;

use ApiPlatform\Metadata\Operation;
use ApiPlatform\State\ProcessorInterface;
use App\Entity\Markers;
use App\Service\QrCodeService;
use Doctrine\ORM\EntityManagerInterface;

class MarkerDataPersister implements ProcessorInterface
{
    public function __construct(
        private ProcessorInterface $decoratedProcessor,
        private QrCodeService $qrCodeService,
        private EntityManagerInterface $entityManager
    ) {}

    public function process(mixed $data, Operation $operation, array $uriVariables = [], array $context = []): mixed
    {
        $result = $this->decoratedProcessor->process($data, $operation, $uriVariables, $context);

        if (
            $data instanceof Markers
            && method_exists($operation, 'getMethod')
            && $operation->getMethod() === 'POST'
        ) {
            $path = $this->qrCodeService->generateQrCodeForMarker($data);
            $data->setQrCode($path); // ✅ met à jour l'entité

            $this->entityManager->flush(); // ✅ persist la modif
        }

        return $result;
    }
}
