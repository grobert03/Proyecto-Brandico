<?php
// src/Controller/AdminController.php
namespace App\Controller;

use App\Entity\Empresa;
use App\Entity\Usuario;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;

class AdminController extends AbstractController
{
    /**
     * @Route("/usuarios", name="devolver_usuarios")
     */
    public function devolverUsuarios(Request $request)
    {
        $rol = $request->request->get('rol');
        $usu = $request->request->get('usuarios');
        $emp = $request->request->get('empresas');

        $em = $this->getDoctrine()->getManager();
        $json = [];
        $i = 0;
        $usuarios = $em->getRepository(Usuario::class)->findAll();
        $empresas = $em->getRepository(Empresa::class)->findAll();
       
        if ($usu == "true") {
            foreach ($usuarios as $usu) {
                if ($rol == "true") {
                    if ($usu->getRol() == 1) {
                        $json[$i]['correo'] = $usu->getCorreo();
                        $json[$i]['dni'] = $usu->getDni();
                        $json[$i]['nombre'] = $usu->getNombre();
                        $json[$i]['apellidos'] = $usu->getApellidos();
                        $json[$i]['telefono'] = $usu->getTelefono();
                        $json[$i]['rol'] = $usu->getRol();
                        $i++;
                    }
                } else {
                    $json[$i]['correo'] = $usu->getCorreo();
                    $json[$i]['dni'] = $usu->getDni();
                    $json[$i]['nombre'] = $usu->getNombre();
                    $json[$i]['apellidos'] = $usu->getApellidos();
                    $json[$i]['telefono'] = $usu->getTelefono();
                    $json[$i]['rol'] = $usu->getRol();
                    $i++;
                }
            }
        }

        if ($emp == "true" && $rol == "false") {
            foreach ($empresas as $e) {
                $json[$i]['correo'] = $e->getCorreo();
                $json[$i]['cif'] = $e->getCif();
                $json[$i]['nombre'] = $e->getNombre();
                $json[$i]['telefono'] = $e->getTelefono();
                $json[$i]['direccion'] = $e->getDireccion();
                $json[$i]['provincia'] = $e->getProvincia();
                $i++;
            }
        }

        return new JsonResponse($json);
    }
}
