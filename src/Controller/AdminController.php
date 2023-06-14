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
        $rol = ($request->request->get('rol')) == "true" ? true : false;
        $usu = ($request->request->get('usuarios')) == "true" ? true : false;
        $emp = ($request->request->get('empresas')) == "true" ? true : false;
        $correo = $request->request->get('correo');
        $nombre = $request->request->get('nombre');
        $direccion = $request->request->get('direccion');
        $provincia = $request->request->get('provincia');
        $cif = $request->request->get('cif');

        $em = $this->getDoctrine()->getManager();
        $json = [];

        if ($usu && $emp) {
            $tipo = "(0,1)";
        } else if ($usu) {
            $tipo = "(0)";
        } else {
            $tipo = "(1)";
        }

        if ($rol) {
            $rol = "(1)";
        } else {
            $rol = "(0,1)";
        }

        $qb = $em->getRepository(Usuario::class)->createQueryBuilder('u');
        $qb->andWhere("u.nombre LIKE '$nombre%'")->andWhere("u.correo LIKE '$correo%'")->andWhere("u.es_empresa in $tipo")->andWhere("u.rol in $rol");

        
        $usuarios = $qb->getQuery()->getResult();

        foreach ($usuarios as $u) {
            if (str_contains(strtolower($u->getDireccion()), strtolower($direccion)) && str_contains(strtolower($u->getProvincia()), strtolower($provincia)) && str_contains(strtolower($u->getCif()), strtolower($cif))) {
                if ($u->getEs_empresa()) {
                    $json[] = ['id' => $u->getId(), 'correo' => $u->getCorreo(), 'nombre' => $u->getNombre(), 'foto' => 'img/perfiles/'.$u->getFoto(), 'es_empresa' => $u->getEs_empresa(), 'telefono' => $u->getTelefono(), 'cif' => $u->getCif(), 'direccion' => $u->getDireccion(), 'provincia' => $u->getProvincia()];
                } else {
                    $json[] = ['id' => $u->getId(), 'correo' => $u->getCorreo(), 'nombre' => $u->getNombre(), 'foto' => 'img/perfiles/'.$u->getFoto(), 'es_empresa' => $u->getEs_empresa()];
                }
            }    
        }

        return new JsonResponse($json);
    }

    /**
     * @Route("/borrar", name="borrar_usuario")
     */
    public function borrarUsuario(Request $request)
    {
        $em = $this->getDoctrine()->getManager();

        $id = $request->request->get('id');

        $usu = $em->getRepository(Usuario::class)->findOneBy(['id' => $id]);

        $em->remove($usu);
        $em->flush();
        return new JsonResponse(['borrado' => true]);
    }

    /**
     * @Route("/crearUsu", name="crear_usuario")
     */
    public function crearUsu(Request $request)
    {
        $em = $this->getDoctrine()->getManager();

        $correo = $request->request->get('correo');
        $pass = $request->request->get('pass');
        $nombre = $request->request->get('nombre');
        $tel = $request->request->get('tel');
        $cif = $request->request->get('cif');
        $rol = intval($request->request->get('rol'));
        $direccion = $request->request->get('dir');
        $provincia = $request->request->get('prov');
        $empresa = ($request->request->get('empresa')) == "true" ? true : false;

        $user = $em->getRepository(Usuario::class)->findOneBy(['correo' => $correo]);
        if ($user) {
            return new JsonResponse(['error' => 'El correo ya está en uso!']);
        }

        $usu = new Usuario();
        $usu->setCorreo($correo);
        $usu->setClave(password_hash($pass, PASSWORD_DEFAULT));
        $usu->setNombre($nombre);
        $usu->setFoto('default.png');

        if ($empresa) {
            $user = $em->getRepository(Usuario::class)->findOneBy(['telefono' => $tel]);
            if ($user) {
                return new JsonResponse(['error' => 'El teléfono ya está en uso!']);
            }
            $user = $em->getRepository(Usuario::class)->findOneBy(['cif' => $cif]);
            if ($user) {
                return new JsonResponse(['error' => 'El cif ya está en uso!']);
            }
            $usu->setEs_empresa(1);
            $usu->setTelefono($tel);
            $usu->setCif($cif);
            $usu->setDireccion($direccion);
            $usu->setProvincia($provincia);
            $usu->setRol(0);
        } else {
            $usu->setEs_empresa(0);
            $usu->setRol($rol);
        }

        $em->persist($usu);
        $em->flush();

        return new JsonResponse(['creado' => true]);
    }

    /**
     * @Route("/modificarUsuario", name="modificar_usuario")
     */
    public function modificarUsuario(Request $request)
    {
        $em = $this->getDoctrine()->getManager();

        $buscar = $request->request->get('id');
        $correo = $request->request->get('mod_correo');
        $nombre = $request->request->get('mod_nombre');
        $tipo = $request->request->get('mod_tipo');
        $tel = $request->request->get('mod_tel');
        $cif = $request->request->get('mod_cif');
        $dir = $request->request->get('mod_dir');
        $prov = $request->request->get('mod_prov');

        $usu = $em->getRepository(Usuario::class)->findOneBy(['id' => $buscar]);

        $buscarCorreo = $em->getRepository(Usuario::class)->findOneBy(['correo' => $correo]);

        $buscarTel = $em->getRepository(Usuario::class)->findOneBy(['telefono' => $tel]);

        $buscarCif = $em->getRepository(Usuario::class)->findOneBy(['cif' => $cif]);

        if ($buscarCorreo && $correo != $usu->getCorreo()) {
            return new JsonResponse(['error' => 'El correo ya está en uso!']);
        }

        if ($buscarTel  && $tel != $usu->getTelefono()) {
            return new JsonResponse(['error' => 'El teléfono ya está en uso!']);
        }

        if ($buscarCif && $cif != $usu->getCif()) {
            return new JsonResponse(['error' => 'El CIF ya está en uso!']);
        }

        $usu->setCorreo($correo);
        $usu->setTelefono($tel);
        $usu->setNombre($nombre);
        if ($usu->getEs_empresa() && $tipo == "0") {
            $usu->setDireccion(null);   
            $usu->setProvincia(null);   
            $usu->setCif(null);   
        } else if ($tipo == "1") {
            $usu->setDireccion($dir);   
            $usu->setProvincia($prov);  
            $usu->setCif($cif);   
        }
        $usu->setEs_empresa($tipo);   


        $em->persist($usu);
        $em->flush();

        return new JsonResponse(['modificado' => true]);
    }
}
