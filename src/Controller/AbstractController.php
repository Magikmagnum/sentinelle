<?php

namespace App\Controller;

use OpenApi\Annotations as OA;

/**
 * @OA\Response(
 *  response="NotFound",
 *  description="La resource n'existe pas",
 *  @OA\JsonContent(
 *      @OA\Property(property="message", type="string", exemple="L'article n'existe pas"),
 *      @OA\Property(property="status", type="integer")
 *  )
 * )
 * 
 * @OA\SecurityScheme(bearerFormat="JWT", type="apiKey", securityScheme="bearer"),
 */
class AbstractController extends \Symfony\Bundle\FrameworkBundle\Controller\AbstractController
{ 
    
}
