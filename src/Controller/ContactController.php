<?php

namespace App\Controller;

use PHPMailer\PHPMailer\PHPMailer;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Mailer\Exception\TransportExceptionInterface;
use Symfony\Component\Mailer\Mailer;
use Symfony\Component\Mailer\Transport;
use Symfony\Component\Mailer\Transport\Smtp\EsmtpTransport;
use Symfony\Component\Mime\Email;
use Symfony\Component\Routing\Annotation\Route;

class ContactController extends AbstractController {

    #[Route('/contact', name: 'contact')]
    public function contact(Request $request) {
        $jsonData = $request->getContent();
        $formData = json_decode($jsonData, true);

        $transport = Transport::fromDsn('smtp://brandico.digital@gmail.com:rzknatjlwqbayasw@smtp.gmail.com:587?verify_peer=0');

        // Create a Mailer object
        $mailer = new Mailer($transport);

        // Create an Email object
        $email = (new Email());

        // Set the "From address"
        $email->from($formData['email']);

        // Set the "To address"
        $email->to('brandico.digital@gmail.com');

        // Set a "subject"
        $email->subject($formData['subject']);

        // Set the plain-text "Body"
        //$email->text($formData['message']);

        // Set HTML "Body"
        $email->html('
            <strong>Nombre: </strong>' . $formData['name'] . '<br>' .
            '<strong>Email: </strong>' . $formData['email'] . '<br>' .
            '<strong>Mensaje: </strong><br>' . $formData['message']
        );

        try {
            $mailer->send($email);
            return new JsonResponse(['message' => 'Envío realizado con éxito'], 201);
        } catch (TransportExceptionInterface $e) {
            $errorMessage = $e->getMessage();
            return new JsonResponse(['message' => $errorMessage], 500);
        }
    }
}