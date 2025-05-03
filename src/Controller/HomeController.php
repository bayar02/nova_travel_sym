<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;
use App\Repository\VolRepository;
use Knp\Component\Pager\PaginatorInterface;

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
    public function dashboard(Request $request, VolRepository $volRepository, PaginatorInterface $paginator): Response
    {
        $searchCriteria = [
            'destination' => $request->request->get('destination'),
            'dateStart' => $request->request->get('dateStart'),
            'dateEnd' => $request->request->get('dateEnd'),
        ];
        
        $sortBy = $request->request->get('sortBy', 'date');
        $searchPerformed = !empty($searchCriteria['destination']) || !empty($searchCriteria['dateStart']) || !empty($searchCriteria['dateEnd']);
        
        // Get the query builder from repository
        $queryBuilder = $volRepository->searchFlights($searchCriteria, $sortBy);
        
        // Paginate the results
        $upcomingFlights = $paginator->paginate(
            $queryBuilder,
            $request->query->getInt('page', 1), // Current page number
            4 // Items per page
        );
        
        return $this->render('user/dashboard.html.twig', [
            'upcomingFlights' => $upcomingFlights,
            'searchCriteria' => $searchCriteria,
            'searchPerformed' => $searchPerformed,
            'sortBy' => $sortBy,
        ]);
    }
} 