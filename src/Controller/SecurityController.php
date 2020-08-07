<?php

namespace App\Controller;

use App\Entity\User;
use App\Repository\UserRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use OpenApi\Annotations as OA;

class SecurityController extends AbstractController
{
    
    /**
     * 
     * @Route("/register", name="security_register", methods={"POST"})
     * 
     * @OA\Post(
     *  path="/register",
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
    public function register(Request $request, UserRepository $userRepository, UserPasswordEncoderInterface $encoder)
    {
        
        try {
            $data = json_decode($request->getContent());

            $user = new User();
            $user->setTelephone($data->telephone);
            $user->setRoles(['ROLE_USER']);
            $user->setPassword($encoder->encodePassword($user, $data->password));
    
            $em = $this->getDoctrine()->getManager();
            $em->persist($user);
            $em->flush();
    
            $response = [
                "errors" => false,
                "status" => 201,
                "data" => $user,
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
