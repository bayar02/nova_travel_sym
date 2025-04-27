<?php

namespace App\Controller;

use KnpU\OAuth2ClientBundle\Client\ClientRegistry;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;

class OAuthController extends AbstractController
{
    #[Route('/connect/google', name: 'connect_google')]
    public function connectGoogle(ClientRegistry $clientRegistry): RedirectResponse
    {
        return $clientRegistry
            ->getClient('google')
            ->redirect(['email', 'profile']);
    }

    #[Route('/connect/google/check', name: 'connect_google_check')]
    public function connectGoogleCheck()
    {
        // The authenticator will handle this route
    }

    #[Route('/connect/facebook', name: 'connect_facebook')]
    public function connectFacebook(ClientRegistry $clientRegistry, Request $request): RedirectResponse
    {
        $client = $clientRegistry->getClient('facebook');
        $redirectUrl = $client->getOAuth2Provider()->getAuthorizationUrl();
        
        // Log the redirect URL
        file_put_contents(
            __DIR__ . '/../../var/log/oauth.log',
            date('Y-m-d H:i:s') . " - Facebook redirect URL: " . $redirectUrl . "\n",
            FILE_APPEND
        );
        
        return $client->redirect(['email', 'public_profile']);
    }

    #[Route('/connect/facebook/check', name: 'connect_facebook_check')]
    public function connectFacebookCheck()
    {
        // The authenticator will handle this route
    }
} 