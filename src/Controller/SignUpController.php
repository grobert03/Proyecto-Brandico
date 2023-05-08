<?php

namespace App\Controller;

use App\Entity\Empresa;
use App\Entity\Usuario;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;

class SignUpController extends AbstractController {

    #[Route('/signup', name: 'signup')]
    public function signup() {
       return $this->render('registro.html.twig');
    }

    #[Route('/createuser', name: 'createuser')]
    public function createuser(Request $request) {
        $entityManager = $this->getDoctrine()->getManager();

        $jsonData = $request->getContent();
        $formData = json_decode($jsonData, true);

        $type = $formData['type'];

        if ($type === 'user') {
            //Registrar un usuario
            //Hasheo del password
            $password = password_hash($formData['password'], PASSWORD_DEFAULT);

            $user = new Usuario();
            $user->setCorreo($formData['email']);
            $user->setClave($password);
            $user->setDni($formData['dni']);
            $user->setNombre($formData['name']);
            $user->setApellidos($formData['surname']);
            $user->setTelefono($formData['phone']);
            $user->setRol(0);

            $entityManager->persist($user);
        } elseif ($type === 'company') {
            //Registrar una empresa
            //Hasheo del password
            $password = password_hash($formData['password'], PASSWORD_DEFAULT);

            $company = new Empresa();
            $company->setCorreo($formData['email']);
            $company->setClave($password);
            $company->setCif($formData['cif']);
            $company->setNombre($formData['name']);
            $company->setTelefono($formData['phone']);
            $company->setDireccion($formData['direction']);
            $company->setProvincia($formData['province']);

            $entityManager->persist($company);
        } else {
            //Si el valor del campo type es incorrecto
            return new JsonResponse(['message' => 'Tipo de usuario no válido'], 400);
        }

        $entityManager->flush();

        return new JsonResponse(['message' => 'Registro realizado con éxito'], 201);
    }
}
