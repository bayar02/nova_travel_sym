<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;
use App\Repository\VolRepository;

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
    public function userDashboard(Request $request, VolRepository $volRepository): Response
    {
        // Default to upcoming flights
        $flights = [];
        $searchPerformed = false;
        $searchCriteria = [
            'destination' => '',
            'dateStart' => '',
            'dateEnd' => ''
        ];
        $sortBy = 'date';
        
        // Handle search form submission
        if ($request->isMethod('POST')) {
            $searchPerformed = true;
            
            // Get search parameters
            $searchCriteria = [
                'destination' => $request->request->get('destination'),
                'dateStart' => $request->request->get('dateStart'),
                'dateEnd' => $request->request->get('dateEnd')
            ];
            
            $sortBy = $request->request->get('sortBy', 'date');
            
            // Search flights with the given criteria
            $flights = $volRepository->searchFlights($searchCriteria, $sortBy);
        } else {
            // Default view: show upcoming flights
            $flights = $volRepository->findUpcoming(8);
        }

        return $this->render('user/dashboard.html.twig', [
            'upcomingFlights' => $flights,
            'searchCriteria' => $searchCriteria,
            'sortBy' => $sortBy,
            'searchPerformed' => $searchPerformed
        ]);
    }
} 