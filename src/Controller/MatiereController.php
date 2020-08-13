<?php

namespace App\Controller;

use App\Entity\Matieres;
use App\Repository\MatieresRepository;
use App\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Validator\Validator\ValidatorInterface;

/**
 * @Route("/matieres")
 */
class MatiereController extends AbstractController
{
    /**
     * @Route("/", name="api_matiere_list", methods={"GET"})
     */
    public function index(MatieresRepository $matieresRepository)
    {
        if ($data = $matieresRepository->findAll()) {
            $response = $this->statusCode(Response::HTTP_OK, $data);
        } else {
            $response = $this->statusCode(Response::HTTP_NOT_FOUND);
        }
        return $this->json($response, $response["status"], [], ["groups" => "matiere:new"]);
    }


    /**
     * @Route("/{id}", name="api_matiere_show", methods={"GET"})
     */
    public function show($id, MatieresRepository $matieresRepository)
    {
        if ($data = $matieresRepository->find($id)) {
            $response = $this->statusCode(Response::HTTP_OK, $data);
        } else {
            $response = $this->statusCode(Response::HTTP_NOT_FOUND);
        }
        return $this->json($response, $response["status"], [], ["groups" => "matiere:new"]);
    }


    /**
     * @Route("", name="api_matieres_new", methods={"POST"})
     */
    public function new(Request $request, ValidatorInterface $validator)
    {
        //$this->denyAccessUnlessGranted('ROLE_MASTER');
        $data = json_decode($request->getContent());

        $errors = [];
        isset($data->matiere) ? null : $errors['matiere'] = 'Champs Obligatoir';
        isset($data->abreger) ? null : $errors['abreger'] = 'Champs Obligatoir';

        if (empty($errors)) {

            $matiere = new Matieres;
            $matiere->setMatiere($data->matiere)->setAbreger($data->abreger);

            if (!$response = $this->getErrors($matiere, $validator)) {

                $em = $this->getDoctrine()->getManager();
                $em->persist($matiere);
                $em->flush();

                $response = $this->statusCode(Response::HTTP_CREATED, $matiere);
            }
        } else {
            $response = $this->statusCode(Response::HTTP_BAD_REQUEST, $errors);
        }

        return $this->json($response, $response["status"], [], ["groups" => "matiere:new"]);
    }


    /**
     * @Route("/{id}", name="api_matieres_edit", methods={"PUT"})
     */
    public function edit($id, Request $request, MatieresRepository $matieresRepository, ValidatorInterface $validator)
    {
        if ($matiere = $matieresRepository->find($id)) {

            //$this->denyAccessUnlessGranted('ROLE_MASTER');

            $data = json_decode($request->getContent());

            isset($data->abreger) ? $matiere->setMatiere($data->abreger) : null;
            isset($data->matiere) ? $matiere->setAbreger($data->matiere) : null;

            if (!$response = $this->getErrors($matiere, $validator)) {

                $matiere->setUpdatedAt();
                $em = $this->getDoctrine()->getManager();
                $em->persist($matiere);
                $em->flush();

                $response = $this->statusCode(Response::HTTP_OK, $matiere);
            }
        } else {
            $response = $this->statusCode(Response::HTTP_NOT_FOUND);
        }

        return $this->json($response, $response["status"], [], ["groups" => "matiere:new"]);
    }


    /** 
     * @Route("/{id}", name="api_matieres_delete", methods={"DELETE"})
     */
    public function delete($id, MatieresRepository $matieresRepository)
    {
        if ($matieres = $matieresRepository->find($id)) {

            //$this->denyAccessUnlessGranted("ROLLE_MASTER");

            $em = $this->getDoctrine()->getManager();
            $em->remove($matieres);
            $em->flush();
            $response = $this->statusCode(Response::HTTP_OK);
        } else {
            $response = $this->statusCode(Response::HTTP_NOT_FOUND);
        }

        return $this->json($response, $response["status"]);
    }
}
