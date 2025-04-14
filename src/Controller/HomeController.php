<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;

class HomeController extends AbstractController
{
    #[Route('/home', name: 'app_home')]
    public function index(): Response
    {
        // This route might become obsolete or just a landing page
        return $this->render('home/index.html.twig', [
            'controller_name' => 'HomeController',
        ]);
    }

    #[Route('/user/dashboard', name: 'user_dashboard')]
    #[IsGranted('ROLE_USER')]
    public function userDashboard(): Response
    {
        return $this->render('user/dashboard.html.twig', [
            // Add any data needed for the user dashboard here
        ]);
    }
} 