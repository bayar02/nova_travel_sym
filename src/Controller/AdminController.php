<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\AdminUserType;
use App\Form\RegistrationFormType;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
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

    #[Route('/users', name: 'admin_user_index')]
    public function userIndex(UserRepository $userRepository): Response
    {
        // Fetch all users (you might want pagination for many users)
        $users = $userRepository->findAll(); 

        return $this->render('admin/user/index.html.twig', [
            'users' => $users,
        ]);
    }

    #[Route('/users/new', name: 'admin_user_new')]
    public function userNew(Request $request, UserPasswordHasherInterface $passwordHasher, EntityManagerInterface $entityManager): Response
    {
        $user = new User();
        // Use RegistrationFormType for creation (includes password)
        $form = $this->createForm(RegistrationFormType::class, $user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            // Encode the plain password
             /** @var string $plainPassword */
            $plainPassword = $form->get('plainPassword')->getData();
            $user->setPassword(
                $passwordHasher->hashPassword(
                    $user,
                    $plainPassword
                )
            );

            // Optionally set default roles for admin-created users
            // $user->setRoles(['ROLE_USER']); 

            $entityManager->persist($user);
            $entityManager->flush();

            $this->addFlash('success', 'User created successfully!');

            return $this->redirectToRoute('admin_user_index');
        }

        return $this->render('admin/user/new.html.twig', [
            'registrationForm' => $form->createView(), // Use registrationForm to match template expectations
        ]);
    }

    #[Route('/users/{id}/edit', name: 'admin_user_edit')]
    public function userEdit(Request $request, User $user, EntityManagerInterface $entityManager): Response
    {
        // Use AdminUserType for editing (no password change here)
        $form = $this->createForm(AdminUserType::class, $user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush(); // No need to persist, object is already managed

            $this->addFlash('success', 'User updated successfully!');

            return $this->redirectToRoute('admin_user_index');
        }

        return $this->render('admin/user/edit.html.twig', [
            'user' => $user,
            'editForm' => $form->createView(), // Pass form as editForm
        ]);
    }

    #[Route('/users/{id}', name: 'admin_user_delete', methods: ['POST'])] // Specify POST method
    public function userDelete(Request $request, User $user, EntityManagerInterface $entityManager): Response
    {
        // Prevent deleting the currently logged-in admin (optional safeguard)
        if ($this->getUser() === $user) {
            $this->addFlash('error', 'You cannot delete your own account.');
            return $this->redirectToRoute('admin_user_index');
        }

        if ($this->isCsrfTokenValid('delete'.$user->getId(), $request->getPayload()->getString('_token'))) {
            $entityManager->remove($user);
            $entityManager->flush();
            $this->addFlash('success', 'User deleted successfully!');
        } else {
             $this->addFlash('error', 'Invalid CSRF token.');
        }

        return $this->redirectToRoute('admin_user_index');
    }

    #[Route('/users/{id}/update-role', name: 'admin_user_update_role', methods: ['POST'])]
    public function updateUserRole(Request $request, User $user, EntityManagerInterface $entityManager): JsonResponse
    {
        // Basic security check: prevent modifying own role this way?
        if ($this->getUser() === $user) {
            return $this->json(['success' => false, 'message' => 'Cannot modify own role here.'], Response::HTTP_FORBIDDEN);
        }

        $data = json_decode($request->getContent(), true);
        $newRoles = $data['roles'] ?? null;

        // Basic validation: ensure roles is an array and contains valid roles
        if (!is_array($newRoles) || empty($newRoles)) {
            return $this->json(['success' => false, 'message' => 'Invalid roles data.'], Response::HTTP_BAD_REQUEST);
        }

        // Ensure only valid roles are set (e.g., ROLE_USER, ROLE_ADMIN)
        $validRoles = ['ROLE_USER', 'ROLE_ADMIN']; // Define allowed roles
        $filteredRoles = array_filter($newRoles, fn($role) => in_array($role, $validRoles));

        if (empty($filteredRoles)) {
            // If filtering results in empty, default to ROLE_USER?
            // Or return error? Returning error for now.
             return $this->json(['success' => false, 'message' => 'No valid roles provided.'], Response::HTTP_BAD_REQUEST);
        }
        
        // Prevent removing the last ROLE_ADMIN if needed (optional safeguard)
        // This logic might need adjustment based on how you manage admin roles
        $adminRepository = $entityManager->getRepository(User::class);
        $adminCount = count($adminRepository->findByRole('ROLE_ADMIN'));
        if (in_array('ROLE_ADMIN', $user->getRoles()) && !in_array('ROLE_ADMIN', $filteredRoles) && $adminCount <= 1) {
            return $this->json(['success' => false, 'message' => 'Cannot remove the last admin role.'], Response::HTTP_BAD_REQUEST);
        }

        $user->setRoles($filteredRoles);
        $entityManager->flush();

        return $this->json(['success' => true, 'message' => 'Roles updated successfully.']);
    }

    // Add other admin-specific actions here...
}
