<?php

namespace App\Controller;

use Symfony\Component\HttpFoundation\Response;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class ExceptionController extends AbstractController
{
    public function catchException(\Throwable $exception)
    {
        //dd($exception->getstatusCode(), $exception->getMessage(), );
        
        if ($exception instanceof NotFoundHttpException) {
            $response = [
                "errors" => true,
                "status" => $exception->getstatusCode(),
                "message" => 'Aucun itinéraire trouvé'
            ];
        } else{
            $response = [
                "errors" => true,
                "status" => Response::HTTP_INTERNAL_SERVER_ERROR,
                "message" => $exception->getMessage()
            ];
        }


        return $this->json($response, $response["status"]); 
    }
}