<?php

namespace App\Controller;

use App\Entity\Eleves;
use App\Repository\ClassesRepository;
use App\Controller\AbstractController;
use App\Repository\ElevesRepository;
use App\Repository\TuteursRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Validator\Validator\ValidatorInterface;

/**
 * @Route("/eleves")
 */
class EleveController extends AbstractController
{
    /**
     * @Route("/", name="api_eleve_list", methods={"GET"})
     */
    public function index(ElevesRepository $elevesRepository)
    {
        if ($data = $elevesRepository->findAll()) {
            $response = $this->statusCode(Response::HTTP_OK, $data);
        } else {
            $response = $this->statusCode(Response::HTTP_NOT_FOUND);
        }
        return $this->json($response, $response["status"], [], ["groups" => "eleve:new"]);
    }

    /**
     * @Route("/{id}", name="api_eleve_show", methods={"GET"})
     */
    public function show($id, ElevesRepository $elevesRepository)
    {
        if ($data = $elevesRepository->find($id)) {
            $response = $this->statusCode(Response::HTTP_OK, $data);
        } else {
            $response = $this->statusCode(Response::HTTP_NOT_FOUND);
        }
        return $this->json($response, $response["status"], [], ["groups" => "eleve:new"]);
    }


    /**
     * @Route("/{classId}/{tuteurId}", name="api_eleves_new", methods={"POST"})
     */
    public function new($classId, $tuteurId, Request $request, ClassesRepository $classesRepository, TuteursRepository $tuteursRepository, ValidatorInterface $validator)
    {

        $classe = $classesRepository->find($classId);
        $tuteur = $tuteursRepository->find($tuteurId);

        //$this->denyAccessUnlessGranted('LINK', $classe);
        //$this->denyAccessUnlessGranted('LINK', $tuteur);

        $data = json_decode($request->getContent());

        $errors = [];
        isset($data->nom) ? null : $errors['nom'] = 'Champs Obligatoir';
        isset($data->sexe) ? null : $errors['sexe'] = 'Champs Obligatoir';
        isset($data->dateDeNaissance) ? null : $errors['dateDeNaissance'] = 'Champs Obligatoir';
        isset($data->nationalite) ? null : $errors['nationalite'] = 'Champs Obligatoir';
        isset($data->lieu) ? null : $errors['lieu'] = 'Champs Obligatoir';

        if (empty($errors)) {

            $eleve = new Eleves;
            $eleve->setNom($data->nom)
                ->setSexe($data->sexe)
                ->setDataDeNaissanceAt($this->getDatime($data->dateDeNaissance))
                ->setLieu($data->lieu)
                ->setNationalite($data->nationalite)
                ->setClasse($classe)
                ->setTuteur($tuteur);

            isset($data->prenom) ? $eleve->setPrenom($data->prenom) : null;

            if (!$response = $this->getErrors($eleve, $validator)) {

                $em = $this->getDoctrine()->getManager();
                $em->persist($eleve);
                $em->flush();

                $response = $this->statusCode(Response::HTTP_CREATED, $eleve);
                return $this->json($response, $response["status"], [], ["groups" => "eleve:new"]);
            }
        } else {
            $response = $this->statusCode(Response::HTTP_BAD_REQUEST, $errors);
        }

        return $this->json($response, $response["status"]);
    }


    /**
     * @Route("/{id}", name="api_eleves_edit", methods={"PUT"})
     */
    public function edit($id, Request $request, ElevesRepository $elevesRepository, ValidatorInterface $validator)
    {
        if ($eleve = $elevesRepository->find($id)) {

            //$this->denyAccessUnlessGranted('EDIT', $eleve);

            $data = json_decode($request->getContent());

            isset($data->nom) ? $eleve->setNom($data->nom) : null;
            isset($data->prenom) ? $eleve->setPrenom($data->prenom) : null;
            isset($data->sexe) ? $eleve->setSexe($data->sexe) : null;
            isset($data->dataDeNaissance) ? $eleve->setDataDeNaissanceAt($this->getDatime($data->dataDeNaissance)) : null;
            isset($data->lieu) ? $eleve->setLieu($data->lieu) : null;
            isset($data->nationalite) ? $eleve->setNationalite($data->nationalite) : null;

            if (!$response = $this->getErrors($eleve, $validator)) {

                $eleve->setUpdatedAt();
                $em = $this->getDoctrine()->getManager();
                $em->persist($eleve);
                $em->flush();

                $response = $this->statusCode(Response::HTTP_OK, $eleve);
            }
        } else {
            $response = $this->statusCode(Response::HTTP_NOT_FOUND);
        }

        return $this->json($response, $response["status"], [], ["groups" => "eleve:new"]);
    }


    /** 
     * @Route("/{id}", name="api_eleves_delete", methods={"DELETE"})
     */
    public function delete($id, ElevesRepository $elevesRepository)
    {
        if ($eleves = $elevesRepository->find($id)) {

            //$this->denyAccessUnlessGranted('DELETE', $eleves);

            $em = $this->getDoctrine()->getManager();
            $em->remove($eleves);
            $em->flush();
            $response = $this->statusCode(Response::HTTP_OK);
        } else {
            $response = $this->statusCode(Response::HTTP_NOT_FOUND);
        }


        return $this->json($response, $response["status"]);
    }
}
