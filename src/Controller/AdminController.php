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
        $rol = boolval($request->request->get('rol'));
        $usu = boolval($request->request->get('usuarios'));
        $emp = boolval($request->request->get('empresas'));
        $correo = $request->request->get('correo');
        $nombre = $request->request->get('nombre');
        $direccion = $request->request->get('direccion');
        $provincia = $request->request->get('provincia');
        $cif = $request->request->get('cif');

        $em = $this->getDoctrine()->getManager();
        $json = [];

        $qb = $em->getRepository(Usuario::class)->createQueryBuilder('u');
        $qb->andWhere("u.nombre LIKE '%:nom%'")->setParameter('nom', $nombre)->andWhere("u.correo LIKE '%:cor%'")->setParameter('cor', $correo)->andWhere("u.es_empresa = :usu")->setParameter('usu', $usu)->orWhere("u.es_empresa = :emp")->setParameter("emp", $emp)->andWhere("u.rol = :rol")->setParameter("rol", $rol);
        
        $usuarios = $qb->getQuery()->getResult();

        foreach ($usuarios as $u) {
            if (str_contains(strtolower($u->getDireccion()), strtolower($direccion)) && str_contains(strtolower($u->getProvincia()), strtolower($provincia)) && str_contains(strtolower($u->getCif()), strtolower($cif))) {
                $json[] = ['nombre' => $u->getNombre()];
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
    public function crearUsu(Request $request)
    {
        $em = $this->getDoctrine()->getManager();

        $correo = $request->request->get('correo');
        $pass = $request->request->get('pass');
        $nombre = $request->request->get('nombre');
        $tel = $request->request->get('tel');
        $cif = $request->request->get('cif');
        $rol = intval($request->request->get('rol'));
        $direccion = $request->request->get('direccion');
        $provincia = $request->request->get('provincia');
        $empresa = boolval($request->request->get('empresa'));

        $usu = new Usuario();
        $usu->setCorreo($correo);
        $usu->setClave(password_hash($pass, PASSWORD_DEFAULT));
        $usu->setNombre($nombre);
        $usu->setFoto('default.png');

        if ($empresa) {
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
     * @Route("/modificarEmpresa", name="modificar_empresa")
     */
    public function modificarEmpresa(Request $request)
    {
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
    public function modificarUsuario(Request $request)
    {
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
