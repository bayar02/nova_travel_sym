<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\JsonResponse;

#[Route('/admin')] // Prefix all routes in this controller with /admin
#[IsGranted('ROLE_ADMIN')] // Ensure only admins can access any route here
class AdminController extends AbstractController
{
    #[Route('/currency/modal', name: 'admin_currency_modal')]
    public function currencyModal(): Response
    {
        return $this->render('admin/currency_modal.html.twig');
    }
    #[Route('/', name: 'app_admin')]
    public function index(): Response
    {
        return $this->render('admin/index.html.twig', [
            'controller_name' => 'AdminController',
        ]);
    }

    #[Route('/dashboard', name: 'admin_dashboard')]
    public function dashboard(VolRepository $volRepository): Response
    {
        // Get current date
        $now = new \DateTime();
        $today = new \DateTime('today');
        $tomorrow = new \DateTime('tomorrow');

        // Calculate stats
        $stats = [
            'totalFlights' => $volRepository->createQueryBuilder('v')
                ->select('COUNT(v.id)')
                ->getQuery()
                ->getSingleScalarResult(),
                
            'upcomingFlights' => $volRepository->createQueryBuilder('v')
                ->select('COUNT(v.id)')
                ->where('v.date_depart >= :now')
                ->setParameter('now', $now)
                ->getQuery()
                ->getSingleScalarResult(),
                
            'todayFlights' => $volRepository->createQueryBuilder('v')
                ->select('COUNT(v.id)')
                ->where('v.date_depart >= :today')
                ->andWhere('v.date_depart < :tomorrow')
                ->setParameter('today', $today)
                ->setParameter('tomorrow', $tomorrow)
                ->getQuery()
                ->getSingleScalarResult(),
                
            'totalRevenue' => (float) $volRepository->createQueryBuilder('v')
                ->select('SUM(v.prix)')
                ->getQuery()
                ->getSingleScalarResult() ?? 0
        ];

        return $this->render('admin/dashboard.html.twig', [
            'controller_name' => 'AdminController',
            'stats' => $stats
        ]);
    }

    // --- FLIGHT (VOL) MANAGEMENT --- 

    #[Route('/flights', name: 'admin_flight_index', methods: ['GET'])]
    public function flightIndex(VolRepository $volRepository): Response
    {
        return $this->render('admin/flight/index.html.twig', [
            'vols' => $volRepository->findAll(),
        ]);
    }

    #[Route('/vol/{id}/edit', name: 'admin_vol_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Vol $vol, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(VolType::class, $vol);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();
            $this->addFlash('success', 'Flight updated successfully');
            return $this->redirectToRoute('admin_dashboard');
        }

        return $this->render('admin/vol/edit.html.twig', [
            'vol' => $vol,
            'form' => $form,
        ]);
    }

    #[Route('/vol/{id}/delete', name: 'admin_vol_delete', methods: ['POST'])]
    public function delete(Request $request, Vol $vol, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$vol->getId(), $request->request->get('_token'))) {
            $entityManager->remove($vol);
            $entityManager->flush();
            $this->addFlash('success', 'Flight deleted successfully');
        }

        return $this->redirectToRoute('admin_dashboard');
    }

    // --- Remove User Management --- 
    /*
    #[Route('/users', name: 'admin_user_index')]
    public function userIndex(): Response
    {
        // TODO: Implement user listing logic (fetch users from DB, etc.)
        $users = []; // Placeholder

        return $this->render('admin/user/index.html.twig', [
            'users' => $userRepository->findAll(),
        ]);
    }

    // Add other admin-specific actions here...

    #[Route('/calendar-events', name: 'admin_calendar_events')]
    public function calendarEvents(VolRepository $volRepository): JsonResponse
    {
        $flights = $volRepository->findAll();
        $events = [];

        foreach ($flights as $flight) {
            $events[] = [
                'id' => $flight->getId(),
                'title' => sprintf('%s: %s → %s', 
                    $flight->getCompagnie(),
                    $flight->getAeroportDepart(),
                    $flight->getAeroportArrivee()
                ),
                'start' => $flight->getDateDepart()->format('Y-m-d\TH:i:s'),
                'end' => $flight->getDateArrivee()->format('Y-m-d\TH:i:s'),
                'url' => $this->generateUrl('admin_vol_edit', ['id' => $flight->getId()]),
                'description' => sprintf(
                    'Flight from %s to %s\nPrice: %s€',
                    $flight->getAeroportDepart(),
                    $flight->getAeroportArrivee(),
                    $flight->getPrix()
                ),
                'backgroundColor' => $flight->getDateDepart() > new \DateTime() ? '#4e73df' : '#858796',
            ];
        }

        return new JsonResponse($events);
    }
}
