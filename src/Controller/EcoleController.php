<?php

namespace App\Controller;

use App\Entity\Ecoles;
use OpenApi\Annotations as OA;
use App\Repository\EcolesRepository;
use App\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Validator\Validator\ValidatorInterface;
use Symfony\Component\Serializer\Normalizer\NormalizerInterface;

/**
 * @Route("/ecoles")
 */
class EcoleController extends AbstractController
{

    /**
     * @OA\Get(
     *  path="/ecoles",
     *  tags={"Ecoles"},
     *  security={"bearer"},
     * 
     *  @OA\Response(
     *      response="200",
     *      description="Liste des ecoles inscritent",
     *      @OA\JsonContent(type="array", @OA\Items(ref="#/components/schemas/Ecole")),
     *  ),
     * )
     * 
     * 
     * @Route("", name="api_ecoles_list", methods={"GET"})
     */
    public function index(EcolesRepository $ecolesRepository)
    {
        if ($data = $ecolesRepository->findAll()) {
            $response = $this->statusCode(Response::HTTP_OK, $data);
        } else {
            $response = $this->statusCode(Response::HTTP_NOT_FOUND);
        }
        return $this->json($response, $response["status"], [], ["groups" => "ecole:new"]);
    }


    /**
     * @OA\Get(
     *  path="/ecoles/{id}",
     *  tags={"Ecoles"},
     *  security={"bearer"},
     *  @OA\Parameter(ref="#/components/parameters/id"),
     *  @OA\Response(
     *      response="200",
     *      description="Detail d'une ecole en particulier",
     *      @OA\JsonContent(ref="#/components/schemas/EcoleShow"),
     *  ),
     * )
     * 
     * 
     * @Route("/{id}", name="api_ecoles_show", methods={"GET"})
     */
    public function show($id, EcolesRepository $ecolesRepository, NormalizerInterface $normalizer)
    {
        if ($data = $ecolesRepository->find($id)) {
            $response = $this->statusCode(Response::HTTP_OK, $data);
        } else {
            $response = $this->statusCode(Response::HTTP_NOT_FOUND);
        }
        return $this->json($response, $response["status"], [], ["groups" => "ecole:new"]);
    }


    /**
     * @OA\Post(
     *  path="/ecoles",
     *  tags={"Ecoles"},
     *  security={"bearer"},
     *  
     *  @OA\RequestBody(ref="#/components/requestBodies/EcoleUpdate"),
     *  @OA\Response(
     *      response="200",
     *      description="Detail d'une ecole en particulier",
     *      @OA\JsonContent(ref="#/components/schemas/Ecole"),
     *  ),
     * 
     * )
     * 
     * 
     * @Route("", name="api_ecoles_new", methods={"POST"})
     */
    public function new(Request $request, ValidatorInterface $validator)
    {
        if ($user = $this->getUser()) {

            $data = json_decode($request->getContent());

            if (isset($data->nom)) {

                $ecoles = new Ecoles();
                $ecoles->setNom($data->nom)->setManager($user);
                isset($data->devise) ? $ecoles->setDevise($data->devise) : null;

                if (!$response = $this->getErrors($ecoles, $validator)) {

                    $em = $this->getDoctrine()->getManager();
                    $em->persist($ecoles);
                    $em->flush();
                    $response = $this->statusCode(Response::HTTP_CREATED, $ecoles);
                }
            } else {
                $response = $this->statusCode(Response::HTTP_BAD_REQUEST, ['nom' => 'Champs Obligatoir']);
            }
        } else {
            $response = $this->statusCode(Response::HTTP_UNAUTHORIZED);
        }

        return $this->json($response, $response["status"], [], ["groups" => "ecole:new"]);
    }




    /**
     * @OA\Put(
     *  path="/ecoles/{id}",
     *  tags={"Ecoles"},
     *  security={"bearer"},
     *  @OA\Parameter(ref="#/components/parameters/id"),
     *  @OA\RequestBody(ref="#/components/requestBodies/EcoleUpdate"),
     *  @OA\Response(
     *      response="200",
     *      description="Detail d'une ecole en particulier",
     *      @OA\JsonContent(ref="#/components/schemas/Ecole"),
     *  ),
     * )
     * 
     * 
     * @Route("/{id}", name="api_ecoles_edit", methods={"PUT"})
     */
    public function edit($id, Request $request, EcolesRepository $ecolesRepository, ValidatorInterface $validator)
    {
        if ($ecoles = $ecolesRepository->find($id)) {

            $this->denyAccessUnlessGranted('EDIT', $ecoles);

            $data = json_decode($request->getContent());

            isset($data->nom) ? $ecoles->setDevise($data->nom) : null;
            isset($data->devise) ? $ecoles->setDevise($data->devise) : null;
            $ecoles->setUpdatedAt();

            if (!$response = $this->getErrors($ecoles, $validator)) {
                $em = $this->getDoctrine()->getManager();
                $em->persist($ecoles);
                $em->flush();

                $response = $this->statusCode(Response::HTTP_OK, $ecoles);
            }
        } else {
            $response = $this->statusCode(Response::HTTP_NOT_FOUND);
        }

        return $this->json($response, $response["status"], [], ["groups" => "ecole:new"]);
    }



    /**
     * 
     * @OA\Delete(
     *  path="/ecoles/{id}",
     *  tags={"Ecoles"},
     *  security={"bearer"},
     *  @OA\Parameter(ref="#/components/parameters/id"),
     * )
     * 
     * 
     * @Route("/{id}", name="api_ecoles_delete", methods={"DELETE"})
     */
    public function delete($id, EcolesRepository $ecolesRepository)
    {
        if ($ecoles = $ecolesRepository->find($id)) {

            $this->denyAccessUnlessGranted('DELETE', $ecoles);

            $em = $this->getDoctrine()->getManager();
            $em->remove($ecoles);
            $em->flush();
            $response = $this->statusCode(Response::HTTP_OK);
        } else {
            $response = $this->statusCode(Response::HTTP_NOT_FOUND);
        }


        return $this->json($response, $response["status"], []);
    }
}
