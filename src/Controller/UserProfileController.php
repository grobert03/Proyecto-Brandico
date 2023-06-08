<?php

namespace App\Controller;

use App\Entity\Seguidor;
use App\Entity\Usuario;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class UserProfileController extends AbstractController {
    #[Route('/getprofile/{userId}', name: 'getprofile')]
    public function getProfile(Request $request, $userId) {
        //Encontrar los datos del usuario y renderizar el twig
        $entityManager = $this->getDoctrine()->getManager();
        $usuarioRepository = $entityManager->getRepository(Usuario::class);
        $seguidoresRepository = $entityManager->getRepository(Seguidor::class);

        $user = $usuarioRepository->findOneBy(array('id' => $userId));

        if (!$user) {
            throw $this->createNotFoundException('Usuario no encontrado');
        }

        // Contar los seguidores
        $seguidores = $seguidoresRepository->count(['id_seguido' => $userId]);

        // Verificar si el usuario actual sigue al usuario que se está visualizando
        $userFollows = $seguidoresRepository->findOneBy(['id_seguido' => $userId, 'id_seguidor' => $this->getUser()->getId()]);
        // Guardamos booleano si ha encontrado registro (Si hay registro es que esta siguiendo al usuario)
        $isFollowing = ($userFollows !== null);

        $user->seguidores = $seguidores;
        $user->isFollowing = $isFollowing;

        return $this->render('userProfile.html.twig', ['user' => $user, 'isFollowing' => $isFollowing, 'userId' => $userId]);
    }

    #[Route('follow/{userId}', name: 'follow')]
    public function follow(Request $request, $userId) {
        // Seguir a un usuario según si no le esta siguiendo
        $entityManager = $this->getDoctrine()->getManager();
        $seguidoresRepository = $entityManager->getRepository(Seguidor::class);
        $user = $this->getUser();
        $follows = $seguidoresRepository->findOneBy(['id_seguido' => $userId, 'id_seguidor' => $user->getId()]);

        if ($follows) {
            // Le esta siguiendo por tanto se deja de seguir
           $entityManager->remove($follows);
           $message = 'Ya no sigues a este usuario';
        } else {
            // No le sigue asi que se crea el registro de seguir
            $usuarioRepository = $entityManager->getRepository(Usuario::class);
            $userToFollow = $usuarioRepository->findOneBy(['id' => $userId]);
            $currentUser = $usuarioRepository->findOneBy(['id' => $this->getUser()->getId()]);

            $newFollow = new Seguidor();
            $newFollow->setId_seguido($userToFollow);
            $newFollow->setId_seguidor($currentUser);
            $entityManager->persist($newFollow);
            $message = 'Ahora sigues a este usuario';
        }

        $entityManager->flush();

        return new JsonResponse(['message' => $message]);
    }
}
