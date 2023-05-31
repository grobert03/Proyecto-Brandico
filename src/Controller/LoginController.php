<?php
// src/Controller/EjemploAcceso.php
namespace App\Controller;

use App\Entity\Empresa;
use App\Entity\Usuario;
use Symfony\Component\Mime\Email;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;

class LoginController extends AbstractController
{
	/**
	 * @Route("/", name="promocion")
	 */
	public function promocion()
	{
		return $this->render('landingPage.html.twig');
	}


	/**
	 * @Route("/login", name="controlador_login")
	 */
	public function login()
	{
		return $this->render('login.html.twig');
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
		return $this->render('admin.html.twig');
	}

	/**
	 * @Route("/enviarCorreo", name="enviar_correo")
	 */
	public function enviarVerificacionRegistro(MailerInterface $mailer, Request $request)
	{
		$session = $request->getSession();
		$para = $request->request->get('mail');
		$session->set('codigo', rand(1, 100000));
		$session->set('correo', $para);
		$codigo = $session->get('codigo');
		$fichero = str_replace("/index.php/", "/", $_SERVER['PHP_SELF']);
		$ruta = "http:localhost" . $fichero . "/completar_registro/" . $codigo;
		$email = (new Email())
			->from('brandico@mail.com')
			->to($para)
			->subject('Verifica el registro')
			->html('Pulse en el enlace para seguir con el registro: <a href="' . $ruta . '">Completar</a>');
		$mailer->send($email);

		// ...
		return new JsonResponse(['enviado' => true]);
	}

	/**
	 * @Route("/completar_registro/{codigo}", name="completar_registro")
	 */
	public function completarRegistro($codigo, Request $request) {
		if ($codigo == $request->getSession()->get('codigo')) {
			$request->getSession()->set('codigo', null);
			
			return $this->render('cambiar.html.twig', ['correo' => $request->getSession()->get('correo')]);
		}
	}

	/**
	 * @Route("/cambiar_pass", name="cambiar_pass")
	 */
	public function cambiarPass() {
		$pass = $_POST['pass'];
		$correo = $_POST['correo'];

		$entityManager = $this->getDoctrine()->getManager();

		$usuario = $entityManager->getRepository(Usuario::class)->findOneBy(['correo' => $correo]);

		if (!$usuario) {
			$usuario = $entityManager->getRepository(Empresa::class)->findOneBy(['correo' => $correo]);
			$usuario->setClave(password_hash($pass, PASSWORD_DEFAULT));
		} else {
			$usuario->setClave(password_hash($pass, PASSWORD_DEFAULT));
		}


		$entityManager->persist($usuario);
		$entityManager->flush();

		return $this->render('login.html.twig');
	}
}
