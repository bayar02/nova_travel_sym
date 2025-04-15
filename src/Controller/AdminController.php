<?php

namespace App\Controller;

use App\Entity\Vol;
use App\Repository\VolRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;

#[Route('/admin')] // Prefix all routes in this controller with /admin
#[IsGranted('ROLE_ADMIN')] // Ensure only admins can access any route here
class AdminController extends AbstractController
{
    #[Route('/', name: 'app_admin')]
    public function index(): Response
    {
        return $this->render('admin/index.html.twig', [
            'controller_name' => 'AdminController',
        ]);
    }

    #[Route('/dashboard', name: 'admin_dashboard')]
    public function dashboard(): Response
    {
        // Simple dashboard page for admins
        return $this->render('admin/dashboard.html.twig', [
            'controller_name' => 'AdminController',
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

    // Note: Add/Edit/Delete actions will likely reuse the existing VolController
    // or require creating new actions here specifically for the admin interface.
    // For simplicity, we'll link to the existing Vol CRUD routes for now.
    // You might want dedicated admin routes/templates later.

    // --- Remove User Management --- 
    /*
    #[Route('/users', name: 'admin_user_index')]
    public function userIndex(UserRepository $userRepository): Response // Assuming UserRepository exists
    {
        return $this->render('admin/user/index.html.twig', [
            'users' => $userRepository->findAll(),
        ]);
    }

    #[Route('/users/{id}/update-role', name: 'admin_user_update_role', methods: ['POST'])]
    public function updateUserRole(Request $request, User $user, EntityManagerInterface $entityManager): JsonResponse
    { 
        // ... (Keep or remove based on if you still need user role editing elsewhere)
    }
    */

    // Add other admin-specific actions here...
}
