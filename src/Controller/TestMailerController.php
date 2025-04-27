<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Email;

class TestMailerController extends AbstractController
{
    #[Route('/test-mail', name: 'app_test_mail')]
    public function index(MailerInterface $mailer): Response
    {
        try {
            $email = (new Email())
                ->from('noreply@novatravel.com')
                ->to('bayaraziz2001@gmail.com')
                ->subject('Test email from Nova Travel')
                ->text('This is a test email to verify the mailer configuration.');

            $mailer->send($email);

            return new Response('Test email sent successfully!');
        } catch (\Exception $e) {
            return new Response('Error sending email: ' . $e->getMessage());
        }
    }
} 