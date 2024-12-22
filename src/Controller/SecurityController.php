<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;

class SecurityController extends AbstractController
{
    #[Route('/login', name: 'app_login')]
    public function login(AuthenticationUtils $authenticationUtils): Response
    {
        // Si l'utilisateur est déjà connecté, redirige vers la page d'accueil ou une autre page.
        if ($this->getUser()) {
            return $this->redirectToRoute('app_homepage');
        }

        // Récupère l'erreur d'authentification s'il y en a une
        $error = $authenticationUtils->getLastAuthenticationError();
        // Récupère le dernier identifiant saisi par l'utilisateur
        $lastUsername = $authenticationUtils->getLastUsername();

        return $this->render('security/login.html.twig', [
            'last_username' => $lastUsername,
            'error' => $error,
        ]);
    }

    #[Route('/logout', name: 'app_logout', methods: ['GET'])]
    public function logout(): void
    {
        // Ce contrôleur peut rester vide, la déconnexion est gérée par Symfony.
        throw new \LogicException('This method can be blank - it will be intercepted by the logout key on your firewall.');
    }
}
