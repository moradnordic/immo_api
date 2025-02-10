<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class ApiServiceController extends AbstractController
{
    #[Route('/api/service', name: 'app_api_service')]
    public function index(): Response
    {
        return $this->render('api_service/index.html.twig', [
            'controller_name' => 'ApiServiceController',
        ]);
    }
}
