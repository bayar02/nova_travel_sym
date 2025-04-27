<?php

namespace App\Service;

use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bridge\Twig\Mime\TemplatedEmail;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;

class PasswordResetService
{
    private $mailer;
    private $entityManager;
    private $urlGenerator;

    public function __construct(
        MailerInterface $mailer,
        EntityManagerInterface $entityManager,
        UrlGeneratorInterface $urlGenerator
    ) {
        $this->mailer = $mailer;
        $this->entityManager = $entityManager;
        $this->urlGenerator = $urlGenerator;
    }

    public function sendPasswordResetEmail(User $user): void
    {
        // Generate a unique token
        $token = bin2hex(random_bytes(32));
        $user->setResetToken($token);
        $this->entityManager->flush();

        // Generate the reset URL
        $resetUrl = $this->urlGenerator->generate('app_reset_password', [
            'token' => $token
        ], UrlGeneratorInterface::ABSOLUTE_URL);

        // Create and send the email
        $email = (new TemplatedEmail())
            ->from('noreply@novatravel.com')
            ->to($user->getMail())
            ->subject('Password Reset Request')
            ->htmlTemplate('emails/reset_password.html.twig')
            ->context([
                'user' => $user,
                'resetUrl' => $resetUrl,
            ]);

        $this->mailer->send($email);
    }
} 