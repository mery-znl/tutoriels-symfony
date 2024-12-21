<?php

namespace App\DataFixtures;

use App\Entity\Matiere;
use App\Entity\Tutoriel;
use App\Entity\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class AppFixtures extends Fixture
{
    private UserPasswordHasherInterface $passwordHasher;

    public function __construct(UserPasswordHasherInterface $passwordHasher)
    {
        $this->passwordHasher = $passwordHasher;
    }

    public function load(ObjectManager $manager): void
    {
        // Ajouter des utilisateurs avec différents rôles
        $roles = [
            ['email' => 'admin@example.com', 'roles' => ['ROLE_ADMIN'], 'password' => 'admin123', 'firstName' => 'Admin', 'lastName' => 'User'],
            ['email' => 'user@example.com', 'roles' => ['ROLE_USER'], 'password' => 'user123', 'firstName' => 'Regular', 'lastName' => 'User'],
            ['email' => 'banned@example.com', 'roles' => ['ROLE_BANNED'], 'password' => 'banned123', 'firstName' => 'Banned', 'lastName' => 'User'],
        ];

        foreach ($roles as $roleData) {
            $user = new User();
            $user->setEmail($roleData['email']);
            $user->setRoles($roleData['roles']);
            $user->setFirstName($roleData['firstName']);
            $user->setLastName($roleData['lastName']);
            $hashedPassword = $this->passwordHasher->hashPassword($user, $roleData['password']);
            $user->setPassword($hashedPassword);
            $manager->persist($user);
        }

        // Ajouter des Matières et des Tutoriels
        for ($i = 1; $i <= 5; $i++) {
            $matiere = new Matiere();
            $matiere->setNom("Matiere $i");
            $matiere->setDescription("Description de la matière $i");
            $manager->persist($matiere);

            for ($j = 1; $j <= 3; $j++) {
                $tutoriel = new Tutoriel();
                $tutoriel->setTitre("Tutoriel $j pour Matiere $i");
                $tutoriel->setContenu("Contenu du tutoriel $j pour Matiere $i");
                $tutoriel->setMatiere($matiere);
                $manager->persist($tutoriel);
            }
        }

        $manager->flush();
    }
}
