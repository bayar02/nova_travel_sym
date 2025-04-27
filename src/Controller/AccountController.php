<?php

namespace App\Controller;

use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;

class AccountController extends AbstractController
{
    #[Route('/account/delete', name: 'app_account_delete', methods: ['POST'])]
    public function deleteAccount(
        Request $request,
        EntityManagerInterface $entityManager,
        TokenStorageInterface $tokenStorage
    ): Response {
        if (!$this->isCsrfTokenValid('delete-account', $request->request->get('_token'))) {
            return $this->redirectToRoute('app_profile');
        }

        $user = $this->getUser();
        if (!$user instanceof User) {
            return $this->redirectToRoute('app_login');
        }

        // Log out the user
        $tokenStorage->setToken(null);
        $request->getSession()->invalidate();

        // Delete the user
        $entityManager->remove($user);
        $entityManager->flush();

        $this->addFlash('success', 'Your account has been deleted successfully.');
        return $this->redirectToRoute('app_home');
    }
} 