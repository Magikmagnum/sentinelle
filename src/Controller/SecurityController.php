<?php

namespace App\Controller;

use App\Entity\User;
use OpenApi\Annotations as OA;
use App\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Validator\Validator\ValidatorInterface;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class SecurityController extends AbstractController
{
    
    /**
     * 
     * @Route("/register", name="security_register", methods={"POST"})
     * 
     * @OA\Post(
     *  path="/register",
     *  tags={"Securities"},
     *  security={"bearer"},
     *  @OA\RequestBody(
     *      request="Register",
     *      description="Corp de la requete",
     *      required=true,
     *      @OA\JsonContent(
     *          @OA\Property(type="string", property="telephone", required=true, example="+24104609874"),
     *          @OA\Property(type="string", property="passeword", required=true, example="emileA15ans"),
     *      )
     *  ), 
     * 
     *  @OA\Response(
     *      response="200",
     *      description="Inscription",
     *      @OA\JsonContent(ref="#/components/schemas/Security"),
     *  ),
     * 
     *  @OA\Response( response="404", ref="#/components/responses/NotFound" )
     * 
     * )
     */
    public function register(Request $request, UserPasswordEncoderInterface $encoder, ValidatorInterface $validator)
    {
        
        $data = json_decode($request->getContent());

        $errors = [];
        isset($data->telephone) ? null : $errors['telephone'] = 'Champs Obligatoir';
        isset($data->password) ? null : $errors['password'] = 'Champs Obligatoir';
        
        if(empty($errors)){
            
            $user = new User();
            $user->setTelephone($data->telephone);
            $user->setRoles(['ROLE_USER']);
            $user->setPassword($encoder->encodePassword($user, $data->password));
            
            if (!$response = $this->getErrors($user, $validator)) {

                $em = $this->getDoctrine()->getManager();
                $em->persist($user);
                $em->flush();
                $response = $this->statusCode(Response::HTTP_CREATED, $user);
            }

        } else {
            $response = $this->statusCode(Response::HTTP_BAD_REQUEST);
        }

        return $this->json($response, $response["status"], []);
    }
}
