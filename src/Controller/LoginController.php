<?php
// src/Controller/EjemploAcceso.php
namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;
use Symfony\Component\HttpFoundation\Session\SessionInterface;

class LoginController extends AbstractController
{
	/**
	 * @Route("/", name="promocion")
	 */
	public function promocion() {
		return $this->render('promocion.html.twig');
	}


	/**
     * @Route("/login", name="controlador_login")
     */
    public function login(){    
        return $this->render('acceso.html.twig');
    }
	
	/**
	 * @Route("/logout", name="controlador_logout")
	 */
	public function logout()
	{
		// Este método nunca se llamará, pero es necesario para crear la ruta "/logout".
		return;
	}	
	
	/**
	 * @Route("/portalinicio", name="controlador_portal_inicio")
	 */
	public function portalInicio()
	{
		// Comprobamos si el usuario al menos se ha logueado
		$this->denyAccessUnlessGranted('IS_AUTHENTICATED_FULLY');
		
		return $this->render('inicio.html.twig');
	}
	
	/**
	 * @Route("/portaladmin", name="controlador_portal_admin")
	 */
	public function portalAdmin()
	{
		$this->denyAccessUnlessGranted('ROLE_ADMIN');
		return new Response("<h1>Hola, administrador (con código PHP)</h1>");
	}	
	

}