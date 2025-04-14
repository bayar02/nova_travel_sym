<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\RegistrationFormType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;

class AuthController extends AbstractController
{
    #[Route('/login', name: 'login')]
    public function login(AuthenticationUtils $authenticationUtils, Request $request): Response
    {
        // get the login error if there is one
        $error = $authenticationUtils->getLastAuthenticationError();

        // last username entered by the user
        $lastUsername = $authenticationUtils->getLastUsername();

        // --- Registration Form Logic --- 
        $user = new User();
        $registrationForm = $this->createForm(RegistrationFormType::class, $user);
        // Note: We are NOT handling the form submission here.
        // It assumes a separate controller (e.g., RegistrationController)
        // handles the POST request to the 'app_register' route.
        // If you want *this* controller to also handle registration POST,
        // you'd need to add `$registrationForm->handleRequest($request);` 
        // and the processing logic (if ($registrationForm->isSubmitted() && $registrationForm->isValid()) { ... })

        // Render the correct template with necessary variables
        return $this->render('security/login.html.twig', [
            'last_username' => $lastUsername,
            'error'         => $error,
            'registrationForm' => $registrationForm->createView(),
        ]);
    }

    #[Route('/logout', name: 'logout', methods: ['GET'])]
    public function logout(): void
    {
        // controller can be blank: it will never be called!
        throw new \Exception('Don\'t forget to activate logout in security.yaml');
    }
} 