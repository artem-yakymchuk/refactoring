<?php

declare(strict_types=1);

namespace App\Serializer;

use Symfony\Component\PropertyInfo\Extractor\ConstructorExtractor;
use Symfony\Component\PropertyInfo\Extractor\ReflectionExtractor;
use Symfony\Component\Serializer\Encoder\JsonEncoder;
use Symfony\Component\Serializer\Normalizer\ObjectNormalizer;
use Symfony\Component\Serializer\Serializer;
use Symfony\Component\Serializer\SerializerInterface;

class SerializerFactory
{
    public function getSerializer(): SerializerInterface //TODO use DI instead
    {
        $extractors = new ConstructorExtractor(
            [new ReflectionExtractor()]
        );
        $normalizers = [
            new ObjectNormalizer(null, null, null, $extractors),
        ];

        $encoders = [new JsonEncoder()];

        return new Serializer($normalizers, $encoders);
    }
}
