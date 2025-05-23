<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Bundle\SecurityBundle\Security;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class HomeController extends AbstractController
{
    #[Route('/home', name: 'app_home')]
    public function index(Security $security): Response
    {
        // Récupérer l'utilisateur actuellement connecté
        $user = $security->getUser();

        $roles = $user ? $user->getRoles() : [];

        return $this->render('home/index.html.twig', [
            'roles' => $roles,
        ]);

    }
}
