<?php

namespace App\Serializer\Normalizer;

use Symfony\Component\Serializer\Normalizer\CacheableSupportsMethodInterface;
use Symfony\Component\Serializer\Normalizer\NormalizerInterface;
use Symfony\Component\Serializer\Normalizer\ObjectNormalizer;
use OpenApi\Annotations as OA;

/**
 * 
 * @OA\RequestBody(
 *      request="EcoleUpdate",
 *      description="Valeurs du formulaire",
 *      required={"nom"},
 *      @OA\JsonContent(
 *          @OA\Property(type="string", property="nom", example="Lycée Leon Mba"),
 *          @OA\Property(type="string", property="devise", example="Union - Travaile - Justice"),
 *      )
 *  )
 * 
 * @OA\Schema(
 *  schema="Ecole",
 *  description="Ecole",
 *  @OA\Property(type="integer", property="id"),
 *  @OA\Property(type="string", property="nom", example="Lycée Privée Saint Esprit"),
 *  @OA\Property(type="string", property="devise", example="Union - Travail - Justice"),
 *  @OA\Property(type="string", property="createdAt", format="date-time"),
 * )
 * 
 * @OA\Schema(
 *  schema="EcoleShow",
 *  description="Ecole",
 *  allOf={@OA\Schema(ref="#/components/schemas/Ecole")},
 *  
 * )
 */
class EcoleNormalizer implements NormalizerInterface, CacheableSupportsMethodInterface
{ 
    private $normalizer;

    public function __construct(ObjectNormalizer $normalizer)
    {
        $this->normalizer = $normalizer;
    }

    public function normalize($object, $format = null, array $context = array()): array
    {
        $data = $this->normalizer->normalize($object, $format, $context);

        // Here: add, edit, or delete some data

        return $data;
    }

    public function supportsNormalization($data, $format = null): bool
    {
        return $data instanceof \App\Entity\Ecoles;
    }

    public function hasCacheableSupportsMethod(): bool
    {
        return true;
    }
}
