<?php

namespace App\Controller;

use App\Entity\Usuario;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class ExploreController extends AbstractController {
    #[Route('/explorar', name: 'explorar')]
    public function explorar() {
        $this->denyAccessUnlessGranted('IS_AUTHENTICATED_FULLY');
        return $this->render('explorar.html.twig');
    }

    #[Route('/searchusers', name: 'searchusers')]
    public function getUsers(Request $request) {
        $this->denyAccessUnlessGranted('IS_AUTHENTICATED_FULLY');
        //Obtener los usuarios que coincidan con el search bar que introdujo el usuario
        $entityManager = $this->getDoctrine()->getManager();
        $usuarioRepository = $entityManager->getRepository(Usuario::class);

        $jsonData = $request->getContent();
        $searchTerm = json_decode($jsonData, true)['searchTerm'];

        $queryBuilder = $usuarioRepository->createQueryBuilder('u');
        $queryBuilder->where($queryBuilder->expr()->orX(
            $queryBuilder->expr()->like('u.nombre', ':searchTerm'),
            $queryBuilder->expr()->like('u.correo', ':searchTerm'),
        ));
        $queryBuilder->setParameter('searchTerm', $searchTerm . '%');

        $users = $queryBuilder->getQuery()->getResult();

        $reponse = [];
        foreach ($users as $user) {
            $reponse[] = [
                'id' => $user->getId(),
                'nombre' => $user->getNombre(),
                'correo' => $user->getCorreo(),
                'foto' => $user->getFoto(),
            ];
        }

        return new JsonResponse($reponse);
    }
}
