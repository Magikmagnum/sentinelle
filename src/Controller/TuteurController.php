<?php

namespace App\Controller;

use App\Entity\Tuteurs;
use App\Repository\TuteursRepository;
use App\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Validator\Validator\ValidatorInterface;

/**
 * @Route("/tuteurs")
 */
class TuteurController extends AbstractController
{
    /**
     * @Route("/", name="api_tuteur_list", methods={"GET"})
     */
    public function index(TuteursRepository $tuteursRepository)
    {
        if ($data = $tuteursRepository->findAll()) {
            $response = $this->statusCode(Response::HTTP_OK, $data);
        } else {
            $response = $this->statusCode(Response::HTTP_NOT_FOUND);
        }
        return $this->json($response, $response["status"], [], ["groups" => "tuteur:new"]);
    }


    /**
     * @Route("/{id}", name="api_tuteur_show", methods={"GET"})
     */
    public function show($id, TuteursRepository $tuteursRepository)
    {
        if ($data = $tuteursRepository->find($id)) {
            $response = $this->statusCode(Response::HTTP_OK, $data);
        } else {
            $response = $this->statusCode(Response::HTTP_NOT_FOUND);
        }
        return $this->json($response, $response["status"], [], ["groups" => "tuteur:new"]);
    }

    
    /**
     * @Route("", name="api_tuteurs_new", methods={"POST"})
     */
    public function new(Request $request, ValidatorInterface $validator)
    {
        $data = json_decode($request->getContent());

        $errors = [];
        isset($data->nom) ? null : $errors['nom'] = 'Champs Obligatoir';
        isset($data->sexe) ? null : $errors['sexe'] = 'Champs Obligatoir';

        if (empty($errors)) {

            $tuteur = new Tuteurs;
            $tuteur->setSexe($data->sexe)->setNom($data->nom);
            isset($data->prenom) ? $tuteur->setPrenom($data->prenom) : null;
            isset($data->profession) ? $tuteur->setProfession($data->profession) : null;

            if (!$response = $this->getErrors($tuteur, $validator)) {

                $em = $this->getDoctrine()->getManager();
                $em->persist($tuteur);
                $em->flush();

                $response = $this->statusCode(Response::HTTP_CREATED, $tuteur);
            }
        } else {
            $response = $this->statusCode(Response::HTTP_BAD_REQUEST, $errors);
        }
        
        return $this->json($response, $response["status"], [], ["groups" => "tuteur:new"]);
    }


    /**
     * @Route("/{id}", name="api_tuteurs_edit", methods={"PUT"})
     */
    public function edit($id, Request $request, TuteursRepository $tuteursRepository, ValidatorInterface $validator)
    {
        if ($tuteur = $tuteursRepository->find($id)) {
            
            //$this->denyAccessUnlessGranted('EDIT', $tuteur);

            $data = json_decode($request->getContent());

            isset($data->nom) ? $tuteur->setNom($data->nom) : null;
            isset($data->prenom) ? $tuteur->setPrenom($data->prenom) : null;
            isset($data->sexe) ? $tuteur->setSexe($data->sexe) : null;
            isset($data->profession) ? $tuteur->setProfession($data->profession) : null;

            if (!$response = $this->getErrors($tuteur, $validator)) {

                $tuteur->setUpdatedAt();
                $em = $this->getDoctrine()->getManager();
                $em->persist($tuteur);
                $em->flush();

                $response = $this->statusCode(Response::HTTP_OK, $tuteur);
            }
        } else {
            $response = $this->statusCode(Response::HTTP_NOT_FOUND);
        }

        return $this->json($response, $response["status"], [], ["groups" => "tuteur:new"]);
    }


    /** 
     * @Route("/{id}", name="api_tuteurs_delete", methods={"DELETE"})
     */
    public function delete($id, TuteursRepository $tuteursRepository)
    {
        if ($tuteurs = $tuteursRepository->find($id)) {

            //$this->denyAccessUnlessGranted('DELETE', $tuteurs);

            $em = $this->getDoctrine()->getManager();
            $em->remove($tuteurs);
            $em->flush();
            $response = $this->statusCode(Response::HTTP_OK);
        } else {
            $response = $this->statusCode(Response::HTTP_NOT_FOUND);
        }

        return $this->json($response, $response["status"]);
    }
}
