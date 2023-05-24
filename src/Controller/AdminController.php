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

    /**
     * @Route("/borrar", name="borrar_usuario")
     */
    public function borrarUsuario(Request $request) {
        $em = $this->getDoctrine()->getManager();

        $correo = $request->request->get('correo');

        $usu = $em->getRepository(Usuario::class)->findOneBy(['correo' => $correo]);

        if ($usu) {
            $em->remove($usu);
        } else {
            $emp = $em->getRepository(Empresa::class)->findOneBy(['correo' => $correo]);
            $em->remove($emp);
        }
        $em->flush();
        return new JsonResponse(['borrado' => true]);
    }

    /**
     * @Route("/crearUsu", name="crear_usuario")
     */
    public function crearUsu(Request $request) {
        $em = $this->getDoctrine()->getManager();

        $correo = $request->request->get('correo');
        $pass = $request->request->get('pass');
        $nombre = $request->request->get('nombre');
        $tel = $request->request->get('tel');
        $dni = $request->request->get('dni');
        $ape = $request->request->get('ape');
        $rol = $request->request->get('rol');

        $usu = new Usuario();
        $usu->setCorreo($correo);
        $usu->setClave(password_hash($pass, PASSWORD_DEFAULT));
        $usu->setDni($dni);
        $usu->setNombre($nombre);
        $usu->setApellidos($ape);
        $usu->setTelefono($tel);
        $usu->setRol($rol);

        $em->persist($usu);
        $em->flush();

        return new JsonResponse(['creado' => true]);
    }

    /**
     * @Route("/crearEmpresa", name="crear_empresa")
     */
    public function crearEmpresa(Request $request) {
        $em = $this->getDoctrine()->getManager();

        $correo = $request->request->get('correo');
        $pass = $request->request->get('pass');
        $nombre = $request->request->get('nombre');
        $tel = $request->request->get('tel');
        $cif = $request->request->get('cif');
        $dir = $request->request->get('dir');
        $prov = $request->request->get('prov');

        $empresa = new Empresa();
        $empresa->setCorreo($correo);
        $empresa->setClave(password_hash($pass, PASSWORD_DEFAULT));
        $empresa->setCif($cif);
        $empresa->setNombre($nombre);
        $empresa->setTelefono($tel);
        $empresa->setDireccion($dir);
        $empresa->setProvincia($prov);

        $em->persist($empresa);
        $em->flush();

        return new JsonResponse(['creado' => true]);
    }

    /**
     * @Route("/modificarEmpresa", name="modificar_empresa")
     */
    public function modificarEmpresa(Request $request) {
        $em = $this->getDoctrine()->getManager();

        $buscar = $request->request->get('aBuscar');
        $correo = $request->request->get('correo');
        $nombre = $request->request->get('nombre');
        $tel = $request->request->get('telefono');
        $cif = $request->request->get('cif');
        $dir = $request->request->get('direccion');
        $prov = $request->request->get('provincia');

        $empresa = $em->getRepository(Empresa::class)->findOneBy(['correo' => $buscar]);
        $empresa->setCorreo($correo);
        $empresa->setCif($cif);
        $empresa->setNombre($nombre);
        $empresa->setTelefono($tel);
        $empresa->setDireccion($dir);
        $empresa->setProvincia($prov);

        $em->persist($empresa);
        $em->flush();

        return new JsonResponse(['modificado' => true]);
    }

    /**
     * @Route("/modificarUsuario", name="modificar_usuario")
     */
    public function modificarUsuario(Request $request) {
        $em = $this->getDoctrine()->getManager();

        $buscar = $request->request->get('aBuscar');
        $correo = $request->request->get('correo');
        $nombre = $request->request->get('nombre');
        $tel = $request->request->get('telefono');
        $dni = $request->request->get('dni');
        $ape = $request->request->get('apellidos');
        $rol = $request->request->get('rol');

        $usu = $em->getRepository(Usuario::class)->findOneBy(['correo' => $buscar]);
        $usu->setCorreo($correo);
        $usu->setDni($dni);
        $usu->setNombre($nombre);
        $usu->setApellidos($ape);
        $usu->setTelefono($tel);
        $usu->setRol($rol);

        $em->persist($usu);
        $em->flush();

        return new JsonResponse(['modificado' => true]);
    }
}
