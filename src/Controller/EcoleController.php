<?php

namespace App\Controller;

use App\Entity\Ecoles;
use OpenApi\Annotations as OA;
use App\Repository\EcolesRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Component\Serializer\Normalizer\NormalizerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

/**
 * @Route("/ecoles")
 */
class EcoleController extends AbstractController
{
    /**
     * @Route("", name="api_ecoles_list", methods={"GET"})
     */
    public function index(EcolesRepository $ecolesRepository, NormalizerInterface $normalizer)
    {
        $response = [
            "errors" => false,
            "status" => Response::HTTP_CREATED,
            "data" => $ecolesRepository->findAll()
        ];

        return $this->json($response, $response["status"], [], ["groups" => "ecole:new"]);
    }
    
    
    /**
     * @Route("", name="api_ecoles_new", methods={"POST"})
     */
    public function new(Request $request)
    {
        try{

            $user = $this->getUser();

            $data = json_decode($request->getContent());
  
            
            $ecoles = new Ecoles();
            $ecoles->setNom($data->nom)->setDevise($data->devise)->setManager($user);
            
            $em = $this->getDoctrine()->getManager();
            $em->persist($ecoles);
            $em->flush();

            $response = [
                "errors" => false,
                "status" => Response::HTTP_CREATED,
                "data" => $ecole
            ];
            
        } catch(\Throwable $e) {

            $response = [
                "errors" => true,
                "status" => Response::HTTP_BAD_REQUEST ,
                "message" => $e->getMessage()
            ];

        }

        return $this->json($response, $response["status"], [], ["groups" => "ecole:new"]);
    }




    /**
     * @Route("/{id}", name="api_ecoles_edit", methods={"PUT"})
     */
    public function edit($id, Request $request, EcolesRepository $ecolesRepository)
    {
        try{

            

            $ecoles = $ecolesRepository->find($id);
            if($ecoles){
                
                $data = json_decode($request->getContent());
                $ecoles->setNom($data->nom)->setDevise($data->devise)->setUpdatedAt();
                
                $em = $this->getDoctrine()->getManager();
                $em->persist($ecoles);
                $em->flush();
                
                $response = [
                    "errors" => false,
                    "status" => Response::HTTP_OK,
                    "data" => $ecoles
                ];
            }
  
        } catch(\Throwable $e) {

            $response = [
                "errors" => true,
                "status" => Response::HTTP_BAD_REQUEST ,
                "message" => $e->getMessage()
            ];

        }

        return $this->json($response, $response["status"], [], ["groups" => "ecole:new"]);
    }



    /**
     * @Route("/{id}", name="api_ecoles_delete", methods={"DELETE"})
     */
    public function delete($id, EcolesRepository $ecolesRepository)
    {
        
        try {
            if(!$ecoles = $ecolesRepository->find($id)){
                $response = [
                    "errors" => true,
                    "status" => Response::HTTP_NOT_FOUND,
                    "message" => $e->getMessage()
                ]; 
            }else{
                
                //$this->denyAccessUnlessGranted('DELETE', $ecoles);
                
                $em = $this->getDoctrine()->getManager();
                $em->remove($ecoles);
                $em->flush();

                $response = [
                    "errors" => false,
                    "status" => Response::HTTP_OK,
                ];
            }

        } catch (AccessDeniedException $e) {
        
            $response = [
                "errors" => true,
                "status" => Response::HTTP_UNAUTHORIZED,
                "message" => $e->getMessage()
            ];
            
        } catch (\Throwable $e) {
            
            $response = [
                "errors" => true,
                "status" => Response::HTTP_BAD_REQUEST,
                "message" => $e->getMessage()
            ];
            
        }

        return $this->json($response, $response["status"], []);
    }
}
