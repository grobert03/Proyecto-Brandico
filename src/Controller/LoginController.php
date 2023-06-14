<?php
// src/Controller/EjemploAcceso.php
namespace App\Controller;

use App\Entity\Empresa;
use App\Entity\Usuario;
use Symfony\Component\Mime\Email;
use Symfony\Component\Mailer\Mailer;
use Symfony\Component\Mailer\Transport;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Mailer\Exception\TransportExceptionInterface;
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
	public function login(AuthenticationUtils $authenticationUtils, SessionInterface $session)
	{
		if (!$this->getUser()) {
			$mensaje = $session->getFlashBag()->get('mensaje');
			if ($authenticationUtils->getLastAuthenticationError()) {
				$mensaje = "Usuario y/o contraseña incorrectos";
			} else {
				$mensaje = '';
			}
			return $this->render('login.html.twig', ['mensaje' => $mensaje]);
		} else {
			return $this->render('inicio.html.twig');
		}
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
	public function enviarVerificacionRegistro(MailerInterface $mailer, Request $request, SessionInterface $session)
	{
		$para = $request->request->get('mail');
		$recuperacion = 'rec' . rand(739378219, 123890138210);
		$em = $this->getDoctrine()->getManager();
		$usu = $em->getRepository(Usuario::class)->findOneBy(['correo' => $para]);
		if (!$usu) {
			return new JsonResponse(['enviado' => false]);
		}
		$ruta = $this->url_origin($_SERVER);
		$ruta = $ruta . "/proyecto-Brandico/public/recuperarClave/$recuperacion";

		$transport = Transport::fromDsn('smtp://brandico.digital@gmail.com:rzknatjlwqbayasw@smtp.gmail.com:587?verify_peer=0');

		// Create a Mailer object
		$mailer = new Mailer($transport);

		// Create an Email object
		$email = (new Email());

		// Set the "From address"
		$email->from('brandico.digital@gmail.com');

		// Set the "To address"
		$email->to($para);

		// Set a "subject"
		$email->subject('Verifica el cambio de contraseña');

		// Set HTML "Body"
		$email->html('Pulse en el enlace para seguir con el cambio: <a href="' . $ruta . '">Cambiar contraseña</a>');

		try {
			$mailer->send($email);
			$expiracion = new \DateTime(date('Y-m-d H:i:s'));
			$expiracion->add(new \DateInterval('P1D'));
			$usu->setRecuperacion($recuperacion);
			$usu->setExpiracion_rec($expiracion);
			$em->flush();
			return new JsonResponse(['enviado' => true]);
		} catch (TransportExceptionInterface $e) {
			$errorMessage = $e->getMessage();
			return new JsonResponse(['error' => $errorMessage], 500);
		}


		return new JsonResponse(['enviado' => true]);
	}

	private function url_origin($s, $use_forwarded_host = false)
	{

		$ssl = (!empty($s['HTTPS']) && $s['HTTPS'] == 'on') ? true : false;
		$sp = strtolower($s['SERVER_PROTOCOL']);
		$protocol = substr($sp, 0, strpos($sp, '/')) . (($ssl) ? 's' : '');

		$port = $s['SERVER_PORT'];
		$port = ((!$ssl && $port == '80') || ($ssl && $port == '443')) ? '' : ':' . $port;

		$host = ($use_forwarded_host && isset($s['HTTP_X_FORWARDED_HOST'])) ? $s['HTTP_X_FORWARDED_HOST'] : (isset($s['HTTP_HOST']) ? $s['HTTP_HOST'] : null);
		$host = isset($host) ? $host : $s['SERVER_NAME'] . $port;

		return $protocol . '://' . $host;
	}


	/**
	 * @Route("/recuperarClave/{codigo}", name="recuperar_clave")
	 */
	public function recuperarClave($codigo, Request $request)
	{
		$em = $this->getDoctrine()->getManager();
		$usu = $em->getRepository(Usuario::class)->findOneBy(['recuperacion' => $codigo]);
		if (!$usu) {
			return $this->render('login.html.twig');
		} else {
			if ($usu->getExpiracion_rec() < new \DateTime()) {
				$usu->setRecuperacion(null);
				$usu->setExpiracion_rec(null);
				$em->flush();
				return $this->render('login.html.twig', ['aviso' => 'El código ha caducado!']);

			} else {
				return $this->render('cambiar.html.twig', ['id' => $usu->getId()]);

			}
		}
	}

	/**
	 * @Route("/cambiar_pass", name="cambiar_pass")
	 */
	public function cambiarPass()
	{
		if ($_SERVER['REQUEST_METHOD'] == "POST") {
			$pass = $_POST['pass'];
			$id = $_POST['id'];

			$entityManager = $this->getDoctrine()->getManager();

			$usuario = $entityManager->getRepository(Usuario::class)->findOneBy(['id' => $id]);

			if (!$usuario) {
				$usuario = $entityManager->getRepository(Empresa::class)->findOneBy(['id' => $id]);
				$usuario->setClave(password_hash($pass, PASSWORD_DEFAULT));
			} else {
				$usuario->setClave(password_hash($pass, PASSWORD_DEFAULT));
			}
			$usuario->setRecuperacion(null);
			$usuario->setExpiracion_rec(null);

			$entityManager->persist($usuario);
			$entityManager->flush();
		} 
		return $this->render('login.html.twig');
	}
}
