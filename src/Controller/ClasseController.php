<?php

namespace App\Controller;

use App\Entity\Classes;
use App\Repository\SessionsRepository;
use App\Controller\AbstractController;
use App\Repository\ClassesRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Validator\Validator\ValidatorInterface;

/**
 * @Route("/classes")
 */
class ClasseController extends AbstractController
{
    /**
     * @Route("/", name="api_classe_list", methods={"GET"})
     */
    public function index(ClassesRepository $classesRepository)
    {
        if ($data = $classesRepository->findAll()) {
            $response = $this->statusCode(Response::HTTP_OK, $data);
        } else {
            $response = $this->statusCode(Response::HTTP_NOT_FOUND);
        }
        return $this->json($response, $response["status"], [], ["groups" => "classe:new"]);
    }

    /**
     * @Route("/{id}", name="api_classe_show", methods={"GET"})
     */
    public function show($id, ClassesRepository $classesRepository)
    {
        if ($data = $classesRepository->find($id)) {
            $response = $this->statusCode(Response::HTTP_OK, $data);
        } else {
            $response = $this->statusCode(Response::HTTP_NOT_FOUND);
        }
        return $this->json($response, $response["status"], [], ["groups" => "classe:new"]);
    }

    
    /**
     * @Route("/{id}", name="api_classes_new", methods={"POST"})
     */
    public function new($id, Request $request, SessionsRepository $sessionsRepository, ValidatorInterface $validator)
    {

        $session = $sessionsRepository->find($id);
        $this->denyAccessUnlessGranted('EDIT', $session);

        $data = json_decode($request->getContent());

        $errors = [];
        isset($data->classe) ? null : $errors['classe'] = 'Champs Obligatoir';

        if (empty($errors)) {

            $classe = new Classes;
            $classe->setClasse($data->classe)->setSession($session);
            isset($data->nom) ? $classe->setNom($data->nom) : null;

            if (!$response = $this->getErrors($classe, $validator)) {

                $em = $this->getDoctrine()->getManager();
                $em->persist($classe);
                $em->flush();

                $response = $this->statusCode(Response::HTTP_CREATED, $classe);
                return $this->json($response, $response["status"], [], ["groups" => "classe:new"]);
            }
        } else {
            $response = $this->statusCode(Response::HTTP_BAD_REQUEST, $errors);
        }
        
        return $this->json($response, $response["status"]);
    }


    /**
     * @Route("/{id}", name="api_classes_edit", methods={"PUT"})
     */
    public function edit($id, Request $request, ClassesRepository $classesRepository, ValidatorInterface $validator)
    {
        if ($classe = $classesRepository->find($id)) {
            
            $this->denyAccessUnlessGranted('EDIT', $classe);

            $data = json_decode($request->getContent());

            isset($data->classe) ? $classe->setClasse($data->classe) : null;
            isset($data->nom) ? $classe->setNom($data->nom) : null;

            if (!$response = $this->getErrors($classe, $validator)) {

                $classe->setUpdatedAt();
                $em = $this->getDoctrine()->getManager();
                $em->persist($classe);
                $em->flush();

                $response = $this->statusCode(Response::HTTP_OK, $classe);
            }
        } else {
            $response = $this->statusCode(Response::HTTP_NOT_FOUND);
        }

        return $this->json($response, $response["status"], [], ["groups" => "classe:new"]);
    }


    /** 
     * @Route("/{id}", name="api_classes_delete", methods={"DELETE"})
     */
    public function delete($id, ClassesRepository $classesRepository)
    {
        if ($classes = $classesRepository->find($id)) {

            $this->denyAccessUnlessGranted('DELETE', $classes);

            $em = $this->getDoctrine()->getManager();
            $em->remove($classes);
            $em->flush();
            $response = $this->statusCode(Response::HTTP_OK);
        } else {
            $response = $this->statusCode(Response::HTTP_NOT_FOUND);
        }


        return $this->json($response, $response["status"]);
    }
}
