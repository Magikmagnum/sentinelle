<?php

namespace App\Controller;

use DateTime;
use App\Entity\Sessions;
use App\Repository\EcolesRepository;
use App\Controller\AbstractController;
use App\Repository\SessionsRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Validator\Validator\ValidatorInterface;

/**
 * @Route("/sessions")
 */
class SessionController extends AbstractController
{
    /**
     * @Route("/", name="api_session_list", methods={"GET"})
     */
    public function index(SessionsRepository $sessionsRepository)
    {
        if ($data = $sessionsRepository->findAll()) {
            $response = $this->statusCode(Response::HTTP_OK, $data);
        } else {
            $response = $this->statusCode(Response::HTTP_NOT_FOUND);
        }
        return $this->json($response, $response["status"], [], ["groups" => "session:new"]);
    }

    /**
     * @Route("/{id}", name="api_session_show", methods={"GET"})
     */
    public function show($id, SessionsRepository $sessionsRepository)
    {
        if ($data = $sessionsRepository->find($id)) {
            $response = $this->statusCode(Response::HTTP_OK, $data);
        } else {
            $response = $this->statusCode(Response::HTTP_NOT_FOUND);
        }
        return $this->json($response, $response["status"], [], ["groups" => "session:new"]);
    }

    /**
     * @Route("/{id}", name="api_sessions_new", methods={"POST"})
     */
    public function new($id, Request $request, EcolesRepository $ecolesRepository, ValidatorInterface $validator)
    {

        $ecole = $ecolesRepository->find($id);
        $this->denyAccessUnlessGranted('EDIT', $ecole);

        $data = json_decode($request->getContent());

        $errors = [];
        isset($data->debutAt) ? null : $errors['debutAt'] = 'Champs Obligatoir';
        isset($data->finAt) ? null : $errors['finAt'] = 'Champs Obligatoir';

        if (empty($errors)) {

            $session = new Sessions;
            $session->setDebutAt($this->getDatime($data->debutAt))->setFinAt($this->getDatime($data->finAt))->setEcole($ecole);
            isset($data->nom) ? $session->setNom($data->nom) : null;

            if (!$response = $this->getErrors($session, $validator)) {

                $em = $this->getDoctrine()->getManager();
                $em->persist($session);
                $em->flush();

                $response = $this->statusCode(Response::HTTP_CREATED, $session);
            }
        } else {
            $response = $this->statusCode(Response::HTTP_BAD_REQUEST, $errors);
        }


        return $this->json($response, $response["status"], [], ["groups" => "session:new"]);
    }


    /**
     * @Route("/{id}", name="api_sessions_edit", methods={"PUT"})
     */
    public function edit($id, Request $request, SessionsRepository $sessionsRepository, ValidatorInterface $validator)
    {
        if ($session = $sessionsRepository->find($id)) {

            $this->denyAccessUnlessGranted('EDIT', $session);

            $data = json_decode($request->getContent());

            isset($data->debutAt) ? $session->setDebutAt($this->getDatime($data->debutAt)) : null;
            isset($data->finAt) ? $session->setFinAt($this->getDatime($data->finAt)) : null;
            isset($data->nom) ? $session->setNom($data->nom) : null;

            if (!$response = $this->getErrors($session, $validator)) {

                $session->setUpdatedAt();
                $em = $this->getDoctrine()->getManager();
                $em->persist($session);
                $em->flush();

                $response = $this->statusCode(Response::HTTP_OK, $session);
            }
        } else {
            $response = $this->statusCode(Response::HTTP_NOT_FOUND);
        }

        return $this->json($response, $response["status"], [], ["groups" => "session:new"]);
    }


    /** 
     * @Route("/{id}", name="api_sessions_delete", methods={"DELETE"})
     */
    public function delete($id, SessionsRepository $sessionsRepository)
    {
        if ($sessions = $sessionsRepository->find($id)) {

            $this->denyAccessUnlessGranted('DELETE', $sessions);

            $em = $this->getDoctrine()->getManager();
            $em->remove($sessions);
            $em->flush();
            $response = $this->statusCode(Response::HTTP_OK);
        } else {
            $response = $this->statusCode(Response::HTTP_NOT_FOUND);
        }


        return $this->json($response, $response["status"]);
    }
}
