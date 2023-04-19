<?php

namespace App\Controller;

use App\Entity\Usuario;

use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

class UserController extends AbstractController
{
    /**
     * @Route("/agregar_usuario", name="controlador_hash")
     */
    public function registration(UserPasswordHasherInterface $passwordHasher, $usuario)
    {
        // ... e.g. get the user data from a registration form

        $plaintextPassword = $usuario->getPass();

        // hash the password (based on the security.yaml config for the $user class)
        $hashedPassword = $passwordHasher->hashPassword(
            $usuario,
            $plaintextPassword
        );
        $usuario->setPassword($hashedPassword);

        // DOCTINE

        $entityManager = $this->getDoctrine()->getManager();
        $entityManager->persist($usuario);
        $entityManager->flush();

        return $this->forward('App\Controller\Login::login');
    }
}