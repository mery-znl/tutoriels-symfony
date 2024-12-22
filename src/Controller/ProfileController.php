<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/profile', name: 'app_profile')]
class ProfileController extends AbstractController
{
    #[Route('', name: '', methods: ['GET'])]
    public function index(): Response
    {
        // Vérifie si l'utilisateur est connecté
        if (!$this->getUser()) {
            return $this->redirectToRoute('app_login');
        }

        return $this->render('profile/index.html.twig', [
            'controller_name' => 'ProfileController',
            'user' => $this->getUser(), // Envoie les informations utilisateur au template
        ]);
    }
}
