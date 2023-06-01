<?php
// src/Controller/InicioController.php
namespace App\Controller;

use App\Entity\Like;
use App\Entity\Usuario;
use App\Entity\Publicacion;
use App\Entity\Seguidor;
use DateTime;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;

class InicioController extends AbstractController
{       
    /**
     * @Route("/crearPost", name="crear_publicacion")
     */
    public function crearPublicacion(Request $request) {
        $em = $this->getDoctrine()->getManager();
        $fichero = $request->files->get('fichero-formulario');
        $contenido = $request->request->get('contenido-formulario');

        $publicacion = new Publicacion();
        $publicacion->setAutor($this->getUser());
        $publicacion->setFecha(new DateTime());
        $publicacion->setTexto($contenido);
        $publicacion->setImagen($fichero->getClientOriginalName());

        if (!is_null($fichero)) {
            $fichero->move($this->getParameter('kernel.project_dir').'/public/img/publicaciones', $fichero->getClientOriginalName());
        }
    
        $em->persist($publicacion);
        $em->flush();

        return new JsonResponse(['guardado' => true]);
    }

    /**
     * @Route("/devolverPublicaciones", name="devolver_publicaciones")
     */
    public function devolverPub(Request $request) {
        $em = $this->getDoctrine()->getManager();
        $pagina = $request->get('pagina');
        $lista = $this->devolverSeguidos();
        $condicion = '';
        foreach ($lista as $i => $l) {
            $condicion = $condicion.$l;
            if (sizeof($lista) != $i + 1) {
                $condicion = $condicion.',';
            } 
        }
        $qb = $em->getRepository(Publicacion::class)->createQueryBuilder('p');
        $qb->where('p.autor in (:condicion)')->setParameter('condicion', $condicion)->orWhere('p.autor = :usu')->setParameter('usu', $this->getUser()->getId())->setMaxResults(2)->setFirstResult(($pagina - 1) * 2)->orderBy('p.fecha', 'DESC');

        $publicaciones = $qb->getQuery()->getResult();

        $json = [];
        foreach ($publicaciones as $p) {
            $likes = $em->getRepository(Like::class)->findBy(['id_post' => $p->getId()]);
            $le_gusta = $em->getRepository(Like::class)->findOneBy(['id_post' => $p->getId(), 'id_usuario' => $this->getUser()->getId()]);
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
                'imagen' => 'img/publicaciones/'.$p->getImagen()
            ];
        }
        return new JsonResponse($json);
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
     * @Route("/darLike", name="dar_like")
     */
    public function darLike(Request $request) {
        $id = $request->request->get('id');
        $em = $this->getDoctrine()->getManager();
        $post = $em->getRepository(Publicacion::class)->findOneBy(['id' => $id]);

        $like = new Like();
        $like->setId_post($post);
        $like->setId_comentario(null);
        $like->setId_usuario($this->getUser());
        $em->persist($like);
        $em->flush();

        return new JsonResponse(['exito' => true]);
    } 

    /**
     * @Route("/quitarLike", name="quitar_like")
     */
    public function quitarLike(Request $request) {
        $id = $request->request->get('id');
        $em = $this->getDoctrine()->getManager();
    
        $like = $em->getRepository(Like::class)->findOneBy(['id_post' => $id, 'id_usuario' => $this->getUser()->getId()]);
        $em->remove($like);
        $em->flush();

        return new JsonResponse(['exito' => true]);
    } 
}
