<?php

namespace App\DataFixtures;

use App\Entity\Matiere;
use App\Entity\Tutoriel;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class AppFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        // Ajouter des Matières
        for ($i = 1; $i <= 5; $i++) {
            $matiere = new Matiere();
            $matiere->setNom("Matiere $i");
            $matiere->setDescription("Description de la matière $i");
            $manager->persist($matiere);

            // Ajouter des Tutoriels pour chaque Matière
            for ($j = 1; $j <= 3; $j++) {
                $tutoriel = new Tutoriel();
                $tutoriel->setTitre("Tutoriel $j pour Matiere $i");
                $tutoriel->setDescription("Description du tutoriel $j pour Matiere $i");
                $tutoriel->setMatiere($matiere);
                $manager->persist($tutoriel);
            }
        }

        $manager->flush();
    }
}

