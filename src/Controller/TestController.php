<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Email;
use Symfony\Component\Routing\Annotation\Route;

class TestController extends AbstractController
{
    #[Route('/test-mail', name: 'test_mail')]
    public function testMail(MailerInterface $mailer): Response
    {
        $email = (new Email())
            ->from('bayaraziz2001@gmail.com')
            ->to('bayaraziz2001@gmail.com')
            ->subject('Test Email')
            ->text('This is a test email from your Symfony application.');

        try {
            $mailer->send($email);
            return new Response('Email sent successfully!');
        } catch (\Exception $e) {
            return new Response('Error sending email: ' . $e->getMessage());
        }
    }
} 