<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\UserType;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/admin/user')]
class UserController extends AbstractController
{
    // Afficher la liste des utilisateurs
    #[Route('/', name: 'user_index', methods: ['GET'])]
    public function index(UserRepository $userRepository): Response
    {
        return $this->render('admin/user/index.html.twig', [
            'users' => $userRepository->findAll(),
        ]);
    }

    // Créer un nouvel utilisateur
    #[Route('/new', name: 'user_new', methods: ['GET', 'POST'])]
    public function new(Request $request, UserPasswordHasherInterface $passwordHasher, EntityManagerInterface $entityManager): Response
    {
        $user = new User();
        $form = $this->createForm(UserType::class, $user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            // Hacher le mot de passe
            $plainPassword = $form->get('password')->getData();
            if ($plainPassword) {
                $hashedPassword = $passwordHasher->hashPassword($user, $plainPassword);
                $user->setPassword($hashedPassword);
            }

            // Par défaut, ajouter un rôle si aucun n'est défini
            $roles = $user->getRoles();
            if (empty($roles)) {
                $user->setRoles(['ROLE_USER']);
            }

            $entityManager->persist($user);
            $entityManager->flush();

            $this->addFlash('success', 'Nouvel utilisateur créé avec succès.');
            return $this->redirectToRoute('user_index');
        }

        return $this->render('admin/user/new.html.twig', [
            'form' => $form->createView(),
        ]);
    }

   // Modifier un utilisateur existant
#[Route('/{id}/edit', name: 'user_edit', methods: ['GET', 'POST'])]
public function edit(Request $request, User $user, EntityManagerInterface $entityManager): Response
{
    $form = $this->createForm(UserType::class, $user);
    $form->handleRequest($request);

    if ($form->isSubmitted() && $form->isValid()) {
        $entityManager->flush();

        $this->addFlash('success', 'Utilisateur modifié avec succès.');
        return $this->redirectToRoute('user_index');
    }

    // Ajout de la variable 'user' dans le tableau des données à transmettre à Twig
    return $this->render('admin/user/edit.html.twig', [
        'form' => $form->createView(),
        'user' => $user, 
    ]);
}

    

    // Supprimer un utilisateur
    #[Route('/{id}', name: 'user_delete', methods: ['POST'])]
    public function delete(Request $request, User $user, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete' . $user->getId(), $request->request->get('_token'))) {
            $entityManager->remove($user);
            $entityManager->flush();

            $this->addFlash('success', 'Utilisateur supprimé avec succès.');
        }

        return $this->redirectToRoute('user_index');
    }
}