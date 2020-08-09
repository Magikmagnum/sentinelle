<?php

namespace App\Controller;

use Symfony\Component\HttpFoundation\Response;
use Doctrine\DBAL\Exception\UniqueConstraintViolationException;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;
use Symfony\Component\HttpKernel\Exception\AccessDeniedHttpException;

class ExceptionController extends AbstractController
{
    public function catchException(\Throwable $exception)
    {
        //dd($exception->getstatusCode(), $exception->getMessage(), ); Access Denied
        
        if ($exception instanceof NotFoundHttpException) {

            $response = [
                "errors" => true,
                "status" => Response::HTTP_NOT_FOUND ,
                "message" => 'Aucun itinéraire trouvé',
            ];

        } elseif ($exception instanceof UniqueConstraintViolationException) {
        
            $response = [
                "errors" => true,
                "status" => Response::HTTP_BAD_REQUEST,
                "message" => 'Valeur indisponible'
            ];
            
        } elseif ($exception instanceof AccessDeniedException || $exception instanceof AccessDeniedHttpException) {
        
            $response = [
                "errors" => true,
                "status" => Response::HTTP_FORBIDDEN,
                "message" => "Vous n'avez pas les droit requis pour mener cette action"
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