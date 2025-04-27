<?php

namespace App\Security;

use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface;
use KnpU\OAuth2ClientBundle\Client\ClientRegistry;
use KnpU\OAuth2ClientBundle\Security\Authenticator\OAuth2Authenticator;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\RouterInterface;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Exception\AuthenticationException;
use Symfony\Component\Security\Http\Authenticator\Passport\Badge\UserBadge;
use Symfony\Component\Security\Http\Authenticator\Passport\Passport;
use Symfony\Component\Security\Http\Authenticator\Passport\SelfValidatingPassport;

class OAuthAuthenticator extends OAuth2Authenticator
{
    private $clientRegistry;
    private $entityManager;
    private $router;

    public function __construct(ClientRegistry $clientRegistry, EntityManagerInterface $entityManager, RouterInterface $router)
    {
        $this->clientRegistry = $clientRegistry;
        $this->entityManager = $entityManager;
        $this->router = $router;
    }

    public function supports(Request $request): ?bool
    {
        return $request->attributes->get('_route') === 'connect_facebook_check' 
            || $request->attributes->get('_route') === 'connect_google_check';
    }

    public function authenticate(Request $request): Passport
    {
        $client = $this->clientRegistry->getClient(
            $request->attributes->get('_route') === 'connect_facebook_check' ? 'facebook' : 'google'
        );
        
        $accessToken = $this->fetchAccessToken($client);
        
        return new SelfValidatingPassport(
            new UserBadge($accessToken->getToken(), function() use ($accessToken, $client) {
                $userData = $client->fetchUserFromToken($accessToken)->toArray();
                
                $email = $userData['email'] ?? null;
                if (!$email) {
                    throw new AuthenticationException('Email not found in OAuth response');
                }

                $existingUser = $this->entityManager->getRepository(User::class)->findOneBy(['mail' => $email]);
                
                if (!$existingUser) {
                    $existingUser = new User();
                    $existingUser->setMail($email);
                    $existingUser->setNom($userData['last_name'] ?? $userData['family_name'] ?? '');
                    $existingUser->setPrenom($userData['first_name'] ?? $userData['given_name'] ?? '');
                    $existingUser->setPassword(uniqid());
                    $existingUser->setRoles(['ROLE_USER']);
                    $existingUser->setIsVerified(true);

                    $this->entityManager->persist($existingUser);
                    $this->entityManager->flush();
                }

                return $existingUser;
            })
        );
    }

    public function onAuthenticationSuccess(Request $request, TokenInterface $token, string $firewallName): ?Response
    {
        return new RedirectResponse($this->router->generate('app_home'));
    }

    public function onAuthenticationFailure(Request $request, AuthenticationException $exception): ?Response
    {
        return new RedirectResponse($this->router->generate('app_login'));
    }
} 