<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\UserType;
use App\Repository\UserRepository;
use App\Repository\MatiereRepository;
use App\Repository\TutorielRepository;
use App\Repository\ChapitreRepository;
use App\Repository\CommentaireRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Annotation\Route;

class AdminController extends AbstractController
{
    #[Route('/admin', name: 'admin_dashboard')]
    public function dashboard(
        UserRepository $userRepository,
        MatiereRepository $matiereRepository,
        TutorielRepository $tutorielRepository,
        ChapitreRepository $chapitreRepository,
        CommentaireRepository $commentaireRepository
    ): Response {
        // Récupérer les statistiques
        $totalUsers = $userRepository->count([]);
        $totalMatieres = $matiereRepository->count([]);
        $totalTutoriels = $tutorielRepository->count([]);
        $totalChapitres = $chapitreRepository->count([]);
        $totalCommentaires = $commentaireRepository->count([]);

        return $this->render('admin/dashboard.html.twig', [
            'totalUsers' => $totalUsers,
            'totalMatieres' => $totalMatieres,
            'totalTutoriels' => $totalTutoriels,
            'totalChapitres' => $totalChapitres,
            'totalCommentaires' => $totalCommentaires,
        ]);
    }

    #[Route('/admin/users', name: 'admin_user_index')]
    public function userIndex(UserRepository $userRepository): Response
    {
        $users = $userRepository->findAll();

        return $this->render('admin/user/index.html.twig', [
            'users' => $users,
        ]);
    }

    #[Route('/admin/users/new', name: 'admin_user_new')]
    public function createUser(Request $request, EntityManagerInterface $entityManager, UserPasswordHasherInterface $passwordHasher): Response
    {
        $user = new User();
        $form = $this->createForm(UserType::class, $user);

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $user->setPassword($passwordHasher->hashPassword($user, $form->get('password')->getData()));
            $entityManager->persist($user);
            $entityManager->flush();

            return $this->redirectToRoute('admin_user_index');
        }

        return $this->render('admin/user/new.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    #[Route('/admin/users/{id}/edit', name: 'admin_user_edit')]
    public function editUser(User $user, Request $request, EntityManagerInterface $entityManager, UserPasswordHasherInterface $passwordHasher): Response
    {
        $form = $this->createForm(UserType::class, $user);

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            if ($form->get('password')->getData()) {
                $user->setPassword($passwordHasher->hashPassword($user, $form->get('password')->getData()));
            }
            $entityManager->flush();

            return $this->redirectToRoute('admin_user_index');
        }

        return $this->render('admin/user/edit.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    #[Route('/admin/users/{id}/delete', name: 'admin_user_delete', methods: ['POST'])]
    public function deleteUser(User $user, Request $request, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete' . $user->getId(), $request->request->get('_token'))) {
            $entityManager->remove($user);
            $entityManager->flush();
        }

        return $this->redirectToRoute('admin_user_index');
    }
}
