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

    #[Route('/emailavailable', name: 'emailavailable')]
    public function emailavailable(Request $request) {
        $entityManager = $this->getDoctrine()->getManager();
        $usuarioRepository = $entityManager->getRepository(Usuario::class);

        $jsonData = $request->getContent();
        $formData = json_decode($jsonData, true);

        $email = $formData['email'];

        $usuarios = $usuarioRepository->findAll();

        $isAvailable = true;

        //Verificar si el email ya está utilizado
        foreach ($usuarios as $usuario) {
            if ($usuario->getCorreo() === $email) {
                $isAvailable = false;
                break;
            }
        }

        return new JsonResponse(['available' => $isAvailable]);
    }

    #[Route('/phonelavailable', name: 'phoneavailable')]
    public function phoneavailable(Request $request) {
        $entityManager = $this->getDoctrine()->getManager();
        $usuarioRepository = $entityManager->getRepository(Usuario::class);

        $jsonData = $request->getContent();
        $formData = json_decode($jsonData, true);

        $phone = $formData['phone'];

        $usuarios = $usuarioRepository->findAll();

        $isAvailable = true;

        //Verificar si el teléfono ya está utilizado
        foreach ($usuarios as $usuario) {
            if ($usuario->getTelefono() === $phone) {
                $isAvailable = false;
                break;
            }
        }

        return new JsonResponse(['available' => $isAvailable]);
    }

    #[Route('/cifavailable', name: 'cifavailable')]
    public function cifavailable(Request $request) {
        $entityManager = $this->getDoctrine()->getManager();
        $usuarioRepository = $entityManager->getRepository(Usuario::class);

        $jsonData = $request->getContent();
        $formData = json_decode($jsonData, true);

        $cif = $formData['cif'];

        $usuarios = $usuarioRepository->findAll();

        $isAvailable = true;

        //Verificar si el cif ya está utilizado
        foreach ($usuarios as $usuario) {
            if ($usuario->getCif() === $cif) {
                $isAvailable = false;
                break;
            }
        }

        return new JsonResponse(['available' => $isAvailable]);
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
            $user->setNombre($formData['name']);
            $user->setTelefono($formData['phone']);

            //Valores por defecto
            $user->setFoto('default.png');
            $user->setEs_empresa(0);
            $user->setRol(0);

            $entityManager->persist($user);
        } elseif ($type === 'company') {
            //Registrar una empresa
            //Hasheo del password
            $password = password_hash($formData['password'], PASSWORD_DEFAULT);

            $company = new Usuario();
            $company->setCorreo($formData['email']);
            $company->setClave($password);
            $company->setCif($formData['cif']);
            $company->setNombre($formData['name']);
            $company->setEs_empresa(1);
            $company->setTelefono($formData['phone']);
            $company->setDireccion($formData['direction']);
            $company->setProvincia($formData['province']);

            //Valores por defecto
            $company->setFoto('default.png');
            $company->setRol(0);

            $entityManager->persist($company);
        } else {
            //Si el valor del campo type es incorrecto
            return new JsonResponse(['message' => 'Tipo de usuario no válido'], 400);
        }

        $entityManager->flush();

        return new JsonResponse(['message' => 'Registro realizado con éxito'], 201);
    }
}
