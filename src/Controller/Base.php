<?php
namespace App\Controller;
// src/Controller/Base.php
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;
use App\Entity\Empresa;
use App\Entity\Usuario;
use App\Entity\Seguidos;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Email;
use Symfony\Component\Mime\Address;

use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\HttpFoundation\Session\SessionInterface;

/**
 * @Security("is_granted('ROLE_USER')")
 */
class Base extends AbstractController{

    /**
     * @Route("/miperfil", name="miperfil")
     */
    public function miperfil(){
        $authorizationChecker = $this->get('security.authorization_checker');
        if($authorizationChecker->isGranted('ROLE_USUARIO')){
            $usuario = $this->getDoctrine()->getRepository(Usuario::class)->findOneBy(array('correo'  => $this->getUser()->getCorreo()));
            return $this->render("miperfil.html.twig", array('usuario'=>$usuario));
        }elseif ($authorizationChecker->isGranted('ROLE_EMPRESA')){
            $empresa = $this->getDoctrine()->getRepository(Empresa::class)->findOneBy(array('correo'  => $this->getUser()->getCorreo()));
            return $this->render("miperfil.html.twig", array('empresa'=>$empresa));
        }
    }

    /**
     * @Route("/actualizarcorreo", name="actualizarcorreo")
     */
    public function actualizarcorreo(){
        $authorizationChecker = $this->get('security.authorization_checker');
        if($authorizationChecker->isGranted('ROLE_USUARIO')){
            if($_SERVER["REQUEST_METHOD"]=="GET"){
                return $this->render("correo.html.twig");
            }else{
                $entityManager = $this->getDoctrine()->getManager();
                $correos = $this->getDoctrine()->getRepository(Usuario::class)->findBy(array('correo'  => $_POST['correo']));
                if(count($correos)==0){
                    $usuario = $this->getDoctrine()->getRepository(Usuario::class)->findOneBy(array('correo'  => $this->getUser()->getCorreo()));
                    $usuario->setCorreo($_POST['correo']);
                    $entityManager->flush();
                }  
            }
            return $this->redirectToRoute('miperfil');
        }elseif ($authorizationChecker->isGranted('ROLE_EMPRESA')){
            if($_SERVER["REQUEST_METHOD"]=="GET"){
                return $this->render("correo.html.twig");
            }else{
                $entityManager = $this->getDoctrine()->getManager();
                $correos = $this->getDoctrine()->getRepository(Empresa::class)->findBy(array('correo'  =>$_POST['correo']));
                if(count($correos)==0){
                    $empresa = $this->getDoctrine()->getRepository(Empresa::class)->findOneBy(array('correo'  => $this->getUser()->getCorreo()));
                    $empresa->setCorreo($_POST['correo']);
                    $entityManager->flush();
                }
            }
            return $this->redirectToRoute('miperfil');
        }
    }

    /**
     * @Route("/actualizartelefono", name="actualizartelefono")
     */
    public function actualizartelefono(){
        $authorizationChecker = $this->get('security.authorization_checker');
        if($authorizationChecker->isGranted('ROLE_USUARIO')){
            if($_SERVER["REQUEST_METHOD"]=="GET"){
                return $this->render("telefono.html.twig");
            }else{
                $entityManager = $this->getDoctrine()->getManager();
                $usuario = $this->getDoctrine()->getRepository(Usuario::class)->findOneBy(array('correo'  => $this->getUser()->getCorreo()));
                $usuario->setTelefono($_POST['telefono']);
                $entityManager->flush();
                
            }
            return $this->redirectToRoute('miperfil');
        }elseif ($authorizationChecker->isGranted('ROLE_EMPRESA')) {
            if($_SERVER["REQUEST_METHOD"]=="GET"){
                return $this->render("telefono.html.twig");
            }else{
                $entityManager = $this->getDoctrine()->getManager();
                $empresa = $this->getDoctrine()->getRepository(Empresa::class)->findOneBy(array('correo'  => $this->getUser()->getCorreo()));
                $empresa->setTelefono($_POST['telefono']);
                $entityManager->flush();
                
            }
            return $this->redirectToRoute('miperfil');
        }
    }

    /**
     * @Route("/actualizardireccion", name="actualizardireccion")
     */
    public function actualizardireccion(){
        $this->denyAccessUnlessGranted('ROLE_EMPRESA');
        if($_SERVER["REQUEST_METHOD"]=="GET"){
            return $this->render("direccion.html.twig");
        }else{
            $entityManager = $this->getDoctrine()->getManager();
            $empresa = $this->getDoctrine()->getRepository(Empresa::class)->findOneBy(array('correo'  => $this->getUser()->getCorreo()));
            $empresa->setDireccion($_POST['direccion']);
            $entityManager->flush();
            
        }
        return $this->redirectToRoute('miperfil');
    }

    /**
     * @Route("/actualizarprovincia", name="actualizarprovincia")
     */
    public function actualizarprovincia(){
        $this->denyAccessUnlessGranted('ROLE_EMPRESA');
        if($_SERVER["REQUEST_METHOD"]=="GET"){
            return $this->render("provincia.html.twig");
        }else{
            $entityManager = $this->getDoctrine()->getManager();
            $empresa = $this->getDoctrine()->getRepository(Empresa::class)->findOneBy(array('correo'  => $this->getUser()->getCorreo()));
            $empresa->setProvincia($_POST['provincia']);
            $entityManager->flush();
            
        }
        return $this->redirectToRoute('miperfil');
    }
    
}

    

    
