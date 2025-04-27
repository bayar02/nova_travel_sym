<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class DataDeletionController extends AbstractController
{
    #[Route('/data-deletion', name: 'app_data_deletion')]
    public function index(): Response
    {
        return $this->render('data_deletion/index.html.twig');
    }

    #[Route('/data-deletion/status', name: 'app_data_deletion_status')]
    public function status(Request $request): JsonResponse
    {
        $signedRequest = $request->query->get('signed_request');
        
        if (!$signedRequest) {
            return new JsonResponse([
                'url' => $this->generateUrl('app_data_deletion'),
                'confirmation_code' => '123456789'
            ]);
        }

        // Here you would typically:
        // 1. Verify the signed request
        // 2. Process the deletion request
        // 3. Return the status

        return new JsonResponse([
            'status' => 'completed',
            'confirmation_code' => '123456789'
        ]);
    }
} 