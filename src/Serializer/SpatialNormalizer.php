<?php

namespace App\Serializer;

use LongitudeOne\Spatial\PHP\Types\Geometry\Point;
use LongitudeOne\Spatial\PHP\Types\SpatialInterface;
use Symfony\Component\Serializer\Normalizer\NormalizerInterface;
use Symfony\Component\Serializer\Normalizer\DenormalizerInterface;

class SpatialNormalizer implements NormalizerInterface, DenormalizerInterface
{
    public function supportsNormalization($data, string $format = null, array $context = []): bool
    {
        return $data instanceof Point;
    }

    public function normalize($object, string $format = null, array $context = []): array
    {
        /** @var Point $object */
        return [
            'srid' => $object->getSRID(),
            'type' => 'Point',
            'coordinates' => [$object->getX(), $object->getY()],
        ];
    }

    public function supportsDenormalization($data, string $type, string $format = null, array $context = []): bool
    {
        return ($type === Point::class || $type === SpatialInterface::class)
            && is_array($data)
            && isset($data['srid'], $data['type'], $data['coordinates'])
            && $data['type'] === 'Point'
            && is_array($data['coordinates']);
    }

    public function denormalize($data, string $type, string $format = null, array $context = []): Point
    {
        return new Point($data['coordinates'][0], $data['coordinates'][1], $data['srid']);
    }

    public function getSupportedTypes(?string $format): array
    {
        return [
            Point::class => true,
            SpatialInterface::class => true,
        ];
    }
}
