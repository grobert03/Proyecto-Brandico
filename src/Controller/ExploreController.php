<?php

namespace App\Controller;

use App\Entity\Usuario;
use App\Entity\Comentario;
use App\Entity\Seguidor;
use App\Entity\Publicacion;
use App\Entity\Like;
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

    function devolverSeguidos($usu = 0) {
        $em = $this->getDoctrine()->getManager();
        if (!$usu) {
            $usu = $this->getUser()->getId();
        }
        
        $usuarios = $em->getRepository(Seguidor::class)->findBy(['id_seguidor' => $usu]);
        $lista = [];

        foreach($usuarios as $u) {
            array_push($lista, $u->getId_seguido()->getId());
        }

        return $lista;

    }

    /**
     * @Route("/devolverPublicacionesExplorar", name="devolver_publicaciones_explorar")
     */
    public function devolverPubExplorar(Request $request) {
        $em = $this->getDoctrine()->getManager();
        $pagina = $request->get('pagina');
        // Devolver usuarios a los que sigue
        $lista = $this->devolverSeguidos();
        $condicion = '';
        foreach ($lista as $i => $l) {
            $condicion = $condicion.$l;
            if (sizeof($lista) != $i + 1) {
                $condicion = $condicion.',';
            } 
        }
        // Devolver publicaciones
        $qb = $em->getRepository(Publicacion::class)->createQueryBuilder('p');
        $qb->where('p.autor not in (:condicion)')->setParameter('condicion', $condicion)->andWhere('p.autor != :usu')->setParameter('usu', $this->getUser()->getId())->setMaxResults(3)->setFirstResult(($pagina - 1) * 3)->orderBy('p.fecha', 'DESC');

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
                    array_push($array_personas, ['id' => $pe->getId_usuario()->getId(), 'nombre' => $pe->getId_usuario()->getNombre(), 'foto' =>  'img/perfiles/'.$pe->getId_usuario()->getFoto()]);
                }
                
            }


            foreach ($comentarios as $c) {
                $likes_com = $em->getRepository(Like::class)->findBy(['id_comentario' => $c->getId()]);
                $le_gusta_com = $em->getRepository(Like::class)->findOneBy(['id_usuario' => $this->getUser()->getId(), 'id_comentario' => $c->getId()]);
                array_push($array_comentarios, ['id' => $c->getId(), 'correo' => $c->getAutor()->getCorreo(), 'autor' => $c->getAutor()->getNombre(), 'foto' => 'img/perfiles/'.$c->getAutor()->getFoto() , 'fecha' => $c->getFecha(), 'contenido' => $c->getContenido(), 'likes' => sizeof($likes_com), 'le_gusta' => $le_gusta_com ? 1 : 0]);
            }

            if ($le_gusta) {
                $le_gusta = true;
            } else {
                $le_gusta = false;
            }
            $json[] = [
                'id' => $p->getId(),
                'autor' => $p->getAutor()->getNombre(),
                'perfil' => 'img/perfiles/'.$p->getAutor()->getFoto(),
                'correo' => $p->getAutor()->getCorreo(),
                'fecha' => $p->getFecha(),
                'le_gusta' => $le_gusta,
                'likes' => sizeof($likes),
                'texto' => $p->getTexto(),
                'imagen' => $p->getImagen() ? 'img/publicaciones/'.$p->getImagen() : null,
                'video' => $p->getVideo() ? 'videos/publicaciones/'.$p->getVideo() : null,
                'comentarios' => $array_comentarios,
                'personas' => $array_personas
            ];
        }
        // Ordenar por likes
        usort($json, function($a, $b) {
            return $a['likes'] < $b['likes'];
        });
        return new JsonResponse($json);
    }
}
