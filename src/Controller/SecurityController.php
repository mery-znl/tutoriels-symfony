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
        // Si l'utilisateur est déjà connecté, redirige en fonction de ses rôles
        if ($this->getUser()) {
            // Vérifie si l'utilisateur est banni
            if (in_array('ROLE_BANNED', $this->getUser()->getRoles())) {
                // Déconnecter immédiatement les utilisateurs bannis
                $this->addFlash('error', 'Votre compte est banni. Veuillez contacter un administrateur.');
                return $this->redirectToRoute('app_logout');
            }

            // Redirection pour les administrateurs
            if (in_array('ROLE_ADMIN', $this->getUser()->getRoles())) {
                return $this->redirectToRoute('admin_dashboard');
            }

            // Redirection pour les utilisateurs
            return $this->redirectToRoute('app_profile');
        }

        // Récupère l'erreur d'authentification (s'il y en a une)
        $error = $authenticationUtils->getLastAuthenticationError();
        // Récupère le dernier identifiant saisi par l'utilisateur
        $lastUsername = $authenticationUtils->getLastUsername();

        return $this->render('security/login.html.twig', [
            'last_username' => $lastUsername,
            'error' => $error,
        ]);
    }

    #[Route('/banned', name: 'banned_page')]
    public function banned(): Response
    {
        return $this->render('security/banned.html.twig', [
            'message' => 'Votre compte a été banni. Veuillez contacter un administrateur pour plus d\'informations.',
        ]);
    }

    #[Route('/logout', name: 'app_logout', methods: ['GET'])]
    public function logout(): void
    {
        // Ce contrôleur peut rester vide, la déconnexion est gérée par Symfony.
        throw new \LogicException('This method can be blank - it will be intercepted by the logout key on your firewall.');
    }
}
