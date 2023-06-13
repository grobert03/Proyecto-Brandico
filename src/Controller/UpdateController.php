<?php
namespace App\Controller;
// src/Controller/UpdateController.php
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Symfony\Component\HttpFoundation\Response;
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
class UpdateController extends AbstractController{

    /**
     * @Route("/miperfil", name="miperfil")
     */
    public function miperfil(){
        $this->denyAccessUnlessGranted('IS_AUTHENTICATED_FULLY');

        $entityManager = $this->getDoctrine()->getManager();
        $user = $entityManager->getRepository(Usuario::class)->findOneBy(['id' => $this->getUser()->getId()]);

        return $this->render('miperfil.html.twig', ['user' => $user]);
    }

    #[Route('/editarfoto', name: 'editarfoto')]
    public function editarfoto(){
        $this->denyAccessUnlessGranted('IS_AUTHENTICATED_FULLY');

        return $this->render("foto.html.twig", ['user' => $this->getUser()]);
    }

    #[Route('/actualizarfoto', name: 'actualizarfoto')]
    public function actualizarfoto(Request $request){
        $this->denyAccessUnlessGranted('IS_AUTHENTICATED_FULLY');

        $photo = $request->files->get('photo');

        if (!$photo) {
            return new JsonResponse(['message' => 'No se ha proporcionado ninguna foto'], Response::HTTP_BAD_REQUEST);
        }

        $allowedExtensions = ['jpg', 'jpeg', 'png'];
        $extension = $photo->getClientOriginalExtension();
        if (!in_array(strtolower($extension), $allowedExtensions)) {
            // La extensión del archivo no es válida
            return new JsonResponse(['message' => 'No es un formato de imagen válido'], Response::HTTP_BAD_REQUEST);
        }

        // Generar un nombre único para el archivo usando el nombre original
        $originalName = $photo->getClientOriginalName();
        $newFileName = uniqid() . '_' . $originalName;
        $photoPath = $this->getParameter('kernel.project_dir') . '/public/img/perfiles/';
        try {
            $photo->move($photoPath, $newFileName);
        } catch (FileException $e) {
            return new JsonResponse(['message' => 'Error al mover la foto'], Response::HTTP_INTERNAL_SERVER_ERROR);
        }

        // Actualizar la foto del usuario en la base de datos
        $entityManager = $this->getDoctrine()->getManager();
        $user = $this->getDoctrine()->getRepository(Usuario::class)->findOneBy(['id' => $this->getUser()->getId()]);
        if (!$user) {
            return new JsonResponse(['message' => 'Usuario no encontrado'], Response::HTTP_NOT_FOUND);
        }

        try {
            $user->setFoto($newFileName); // Guardar el nombre del archivo en la base de datos
            $entityManager->flush();
        } catch (\Exception $e) {
            return new JsonResponse(['message' => 'Error al actualizar la foto'], Response::HTTP_INTERNAL_SERVER_ERROR);
        }

        return new JsonResponse(['message' => 'Foto actualizada correctamente', 'photo' => $photoPath], Response::HTTP_CREATED);
    }

    #[Route('/editarnombre', name: 'editarnombre')]
    public function editarnombre(){
        $this->denyAccessUnlessGranted('IS_AUTHENTICATED_FULLY');

        return $this->render("nombre.html.twig", ['user' => $this->getUser()]);
    }

    #[Route('/actualizarnombre', name: 'actualizarnombre')]
    public function actualizarnombre(Request $request) {
        $this->denyAccessUnlessGranted('IS_AUTHENTICATED_FULLY');

        $jsonData = $request->getContent();
        $name = json_decode($jsonData, true)['name'];

        $entityManager = $this->getDoctrine()->getManager();
        $user = $this->getDoctrine()->getRepository(Usuario::class)->findOneBy(array('id'  => $this->getUser()->getId()));

        if (!$user) {
            return new JsonResponse(['message' => 'Usuario no encontrado'], Response::HTTP_NOT_FOUND);
        }

        try {
            $user->setNombre($name);
            $entityManager->flush();
        } catch (\Exception $e) {
            return new JsonResponse(['message' => 'Error al actualizar el nombre'], Response::HTTP_INTERNAL_SERVER_ERROR);
        }

        return new JsonResponse(['message' => 'Nombre actualizado correctamente'], 201);
    }

    #[Route('/editarcorreo', name: 'editarcorreo')]
    public function editarcorreo(){
        $this->denyAccessUnlessGranted('IS_AUTHENTICATED_FULLY');

        return $this->render("correo.html.twig", ['user' => $this->getUser()]);
    }

    /**
     * @Route("/actualizarcorreo", name="actualizarcorreo")
     */
    public function actualizarcorreo(Request $request){
        $this->denyAccessUnlessGranted('IS_AUTHENTICATED_FULLY');

        $jsonData = $request->getContent();
        $email = json_decode($jsonData, true)['email'];

        $entityManager = $this->getDoctrine()->getManager();
        $user = $this->getDoctrine()->getRepository(Usuario::class)->findOneBy(array('id'  => $this->getUser()->getId()));

        if (!$user) {
            return new JsonResponse(['message' => 'Usuario no encontrado'], Response::HTTP_NOT_FOUND);
        }

        try {
            $user->setCorreo($email);
            $entityManager->flush();
        } catch (\Exception $e) {
            return new JsonResponse(['message' => 'Error al actualizar el email'], Response::HTTP_INTERNAL_SERVER_ERROR);
        }

        return new JsonResponse(['message' => 'Email actualizado correctamente'], 201);
    }

    #[Route('/editartelefono', name: 'editartelefono')]
    public function editartelefono(){
        $this->denyAccessUnlessGranted('IS_AUTHENTICATED_FULLY');

        return $this->render("telefono.html.twig", ['user' => $this->getUser()]);
    }

    /**
     * @Route("/actualizartelefono", name="actualizartelefono")
     */
    public function actualizartelefono(Request $request){
        $this->denyAccessUnlessGranted('IS_AUTHENTICATED_FULLY');

        $jsonData = $request->getContent();
        $phone = json_decode($jsonData, true)['phone'];

        $entityManager = $this->getDoctrine()->getManager();
        $user = $this->getDoctrine()->getRepository(Usuario::class)->findOneBy(array('id'  => $this->getUser()->getId()));

        if (!$user) {
            return new JsonResponse(['message' => 'Usuario no encontrado'], Response::HTTP_NOT_FOUND);
        }

        try {
            $user->setTelefono($phone);
            $entityManager->flush();
        } catch (\Exception $e) {
            return new JsonResponse(['message' => 'Error al actualizar el teléfono'], Response::HTTP_INTERNAL_SERVER_ERROR);
        }

        return new JsonResponse(['message' => 'Teléfono actualizado correctamente'], 201);
    }

    #[Route('/editarcif', name: 'editarcif')]
    public function editarcif() {
        $this->denyAccessUnlessGranted('IS_AUTHENTICATED_FULLY');

        if ($this->getUser()->getEs_empresa() != 1) {
            throw $this->createAccessDeniedException('Acceso denegado. El usuario no es una empresa.');
        }

        return $this->render("cif.html.twig", ['user' => $this->getUser()]);
    }

    #[Route('/actualizarcif', name: 'actualizarcif')]
    public function actualizarcif(Request $request) {
        $this->denyAccessUnlessGranted('IS_AUTHENTICATED_FULLY');

        if ($this->getUser()->getEs_empresa() != 1) {
            throw $this->createAccessDeniedException('Acceso denegado. El usuario no es una empresa.');
        }

        $jsonData = $request->getContent();
        $cif = json_decode($jsonData, true)['cif'];

        $entityManager = $this->getDoctrine()->getManager();
        $user = $this->getDoctrine()->getRepository(Usuario::class)->findOneBy(array('id'  => $this->getUser()->getId()));

        if (!$user) {
            return new JsonResponse(['message' => 'Usuario no encontrado'], Response::HTTP_NOT_FOUND);
        }

        try {
            $user->setCif($cif);
            $entityManager->flush();
        } catch (\Exception $e) {
            return new JsonResponse(['message' => 'Error al actualizar el CIF'], Response::HTTP_INTERNAL_SERVER_ERROR);
        }

        return new JsonResponse(['message' => 'CIF actualizado correctamente'], 201);
    }

    #[Route('/editardireccion', name: 'editardireccion')]
    public function editardireccion() {
        $this->denyAccessUnlessGranted('IS_AUTHENTICATED_FULLY');

        if ($this->getUser()->getEs_empresa() != 1) {
            throw $this->createAccessDeniedException('Acceso denegado. El usuario no es una empresa.');
        }

        return $this->render("direccion.html.twig", ['user' => $this->getUser()]);
    }

    /**
     * @Route("/actualizardireccion", name="actualizardireccion")
     */
    public function actualizardireccion(Request $request){
        $this->denyAccessUnlessGranted('IS_AUTHENTICATED_FULLY');

        if ($this->getUser()->getEs_empresa() != 1) {
            throw $this->createAccessDeniedException('Acceso denegado. El usuario no es una empresa.');
        }

        $jsonData = $request->getContent();
        $direction = json_decode($jsonData, true)['direction'];

        $entityManager = $this->getDoctrine()->getManager();
        $user = $this->getDoctrine()->getRepository(Usuario::class)->findOneBy(array('id'  => $this->getUser()->getId()));

        if (!$user) {
            return new JsonResponse(['message' => 'Usuario no encontrado'], Response::HTTP_NOT_FOUND);
        }

        try {
            $user->setDireccion($direction);
            $entityManager->flush();
        } catch (\Exception $e) {
            return new JsonResponse(['message' => 'Error al actualizar la dirección'], Response::HTTP_INTERNAL_SERVER_ERROR);
        }

        return new JsonResponse(['message' => 'Dirección actualizada correctamente'], 201);
    }

    #[Route('/editarprovincia', name: 'editarprovincia')]
    public function editarprovincia() {
        $this->denyAccessUnlessGranted('IS_AUTHENTICATED_FULLY');

        if ($this->getUser()->getEs_empresa() != 1) {
            throw $this->createAccessDeniedException('Acceso denegado. El usuario no es una empresa.');
        }

        return $this->render("provincia.html.twig", ['user' => $this->getUser()]);
    }

    /**
     * @Route("/actualizarprovincia", name="actualizarprovincia")
     */
    public function actualizarprovincia(Request $request){
        $this->denyAccessUnlessGranted('IS_AUTHENTICATED_FULLY');

        if ($this->getUser()->getEs_empresa() != 1) {
            throw $this->createAccessDeniedException('Acceso denegado. El usuario no es una empresa.');
        }

        $jsonData = $request->getContent();
        $province = json_decode($jsonData, true)['province'];

        $entityManager = $this->getDoctrine()->getManager();
        $user = $this->getDoctrine()->getRepository(Usuario::class)->findOneBy(array('id'  => $this->getUser()->getId()));

        if (!$user) {
            return new JsonResponse(['message' => 'Usuario no encontrado'], Response::HTTP_NOT_FOUND);
        }

        try {
            $user->setProvincia($province);
            $entityManager->flush();
        } catch (\Exception $e) {
            return new JsonResponse(['message' => 'Error al actualizar la provincia'], Response::HTTP_INTERNAL_SERVER_ERROR);
        }

        return new JsonResponse(['message' => 'Provincia actualizada correctamente'], 201);
    }
    
}

    

    
