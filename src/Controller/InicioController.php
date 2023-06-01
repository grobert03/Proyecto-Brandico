<?php
// src/Controller/InicioController.php
namespace App\Controller;

use App\Entity\Usuario;
use App\Entity\Publicacion;
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
}
