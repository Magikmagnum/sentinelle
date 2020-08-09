<?php

namespace App\Controller;

use OpenApi\Annotations as OA;

/**
 * @OA\Parameter(
 *      name="id",
 *      in="path",
 *      description="ID de la resource",
 *      required=true,
 *      @OA\Schema(type="integer"),
 * )
 * 
 * @OA\Response(
 *  response="NotFound",
 *  description="La resource n'existe pas",
 *  @OA\JsonContent(
 *      @OA\Property(property="message", type="string", example="L'article n'existe pas"),
 *      @OA\Property(property="status", type="integer", example=404)
 *  )
 * ),
 * 
 * @OA\SecurityScheme(bearerFormat="JWT", type="apiKey", securityScheme="bearer"),
 */
class AbstractController extends \Symfony\Bundle\FrameworkBundle\Controller\AbstractController
{ 
    
}
