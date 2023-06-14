<?php

namespace App\Controller;

use App\Entity\Seguidor;
use App\Entity\Comentario;
use App\Entity\Publicacion;
use App\Entity\Like;
use App\Entity\Usuario;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class UserProfileController extends AbstractController {
    #[Route('/getprofile/{userId}', name: 'getprofile')]
    public function getProfile(Request $request, $userId = null) {
        $this->denyAccessUnlessGranted('IS_AUTHENTICATED_FULLY');
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
        $this->denyAccessUnlessGranted('IS_AUTHENTICATED_FULLY');
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

    /**
     * @Route("/devolverPublicacionesPerfil", name="devolver_publicaciones_perfil")
     */
    public function devolverPubPerfil(Request $request) {
        $em = $this->getDoctrine()->getManager();
        $pagina = $request->get('pagina');
        $userId = $request->request->get('userId');

        // Devolver publicaciones
        $qb = $em->getRepository(Publicacion::class)->createQueryBuilder('p');
        $qb->where('p.autor = :userId')->setParameter('userId', $userId)->setMaxResults(3)->setFirstResult(($pagina - 1) * 3)->orderBy('p.fecha', 'DESC');
        $publicaciones = $qb->getQuery()->getResult();

        $json = [];
        foreach ($publicaciones as $p) {
            $likes = $em->getRepository(Like::class)->findBy(['id_post' => $p->getId()]);
            $le_gusta = $em->getRepository(Like::class)->findOneBy(['id_post' => $p->getId(), 'id_usuario' => $this->getUser()->getId()]);
            // Devolver comentarios
            $comentarios = $em->getRepository(Comentario::class)->findBy(['id_post' => $p->getId()], ['fecha' => 'DESC']);
            $array_comentarios = [];
            $array_personas = [];
            $personas = $em->getRepository(Like::class)->findBy(['id_post' => $p->getId()]);
            foreach($personas as $pe) {
                if ($pe->getId_usuario()->getId() != $this->getUser()->getId()) {
                    array_push($array_personas, ['id' => $pe->getId_usuario()->getId(), 'nombre' => $pe->getId_usuario()->getNombre(), 'foto' =>  'perfiles/'.$pe->getId_usuario()->getFoto()]);
                }
                
            }

            foreach ($comentarios as $c) {
                $likes_com = $em->getRepository(Like::class)->findBy(['id_comentario' => $c->getId()]);
                $le_gusta_com = $em->getRepository(Like::class)->findOneBy(['id_usuario' => $this->getUser()->getId(), 'id_comentario' => $c->getId()]);
                array_push($array_comentarios, ['id' => $c->getId(), 'correo' => $c->getAutor()->getCorreo(), 'autor' => $c->getAutor()->getNombre(), 'id_autor' => $c->getAutor()->getId(), 'foto' => 'perfiles/'.$c->getAutor()->getFoto() , 'fecha' => $c->getFecha(), 'contenido' => $c->getContenido(), 'likes' => sizeof($likes_com), 'le_gusta' => $le_gusta_com ? 1 : 0]);
            }

            if ($le_gusta) {
                $le_gusta = true;
            } else {
                $le_gusta = false;
            }
            $json[] = [
                'id' => $p->getId(),
                'id_autor' => $p->getAutor()->getId(),
                'autor' => $p->getAutor()->getNombre(),
                'perfil' => 'perfiles/'.$p->getAutor()->getFoto(),
                'correo' => $p->getAutor()->getCorreo(),
                'fecha' => $p->getFecha(),
                'le_gusta' => $le_gusta,
                'likes' => sizeof($likes),
                'texto' => $p->getTexto(),
                'imagen' => $p->getImagen() ? 'publicaciones/'.$p->getImagen() : null,
                'video' => $p->getVideo() ? 'publicaciones/'.$p->getVideo() : null,
                'comentarios' => $array_comentarios,
                'personas' => $array_personas
            ];
        }
        return new JsonResponse($json);
    }
 
    /**
     * @Route("/crearComPerf", name="crear_comentario_perfil")
     */
    public function crearComentarioPerfil(Request $request) {
        $em = $this->getDoctrine()->getManager();
        $id = $request->request->get('id');
        $contenido = $request->request->get('texto');

        $comentario = new Comentario();
        $comentario->setId_post($em->getRepository(Publicacion::class)->findOneBy(['id' => $id]));
        $comentario->setAutor($this->getUser());
        $comentario->setFecha(new \DateTime());
        $comentario->setContenido($contenido);

        $em->persist($comentario);
        $em->flush();

        return new JsonResponse(['comentario' => ['id' => $comentario->getId(), 
        'id_autor' => $comentario->getAutor()->getId(), 'autor' => $comentario->getAutor()->getNombre(), 'foto' => 'perfiles/'.$comentario->getAutor()->getFoto() , 'fecha' => $comentario->getFecha(), 'contenido' => $comentario->getContenido(), 'likes' => 0, 'le_gusta' => 0]]);
    }

}
