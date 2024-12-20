<?php

namespace App\Entity;

use App\Repository\ChapitreRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: ChapitreRepository::class)]
class Chapitre
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $titre = null;

    #[ORM\Column(type: Types::TEXT, nullable: true)]
    private ?string $contenu = null;

    /**
     * @var Collection<int, Tutoriel>
     */
    #[ORM\ManyToMany(targetEntity: Tutoriel::class, inversedBy: 'chapitres')]
    private Collection $tutoriel;

    public function __construct()
    {
        $this->tutoriel = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getTitre(): ?string
    {
        return $this->titre;
    }

    public function setTitre(string $titre): static
    {
        $this->titre = $titre;

        return $this;
    }

    public function getContenu(): ?string
    {
        return $this->contenu;
    }

    public function setContenu(?string $contenu): static
    {
        $this->contenu = $contenu;

        return $this;
    }

    /**
     * @return Collection<int, Tutoriel>
     */
    public function getTutoriel(): Collection
    {
        return $this->tutoriel;
    }

    public function addTutoriel(Tutoriel $tutoriel): static
    {
        if (!$this->tutoriel->contains($tutoriel)) {
            $this->tutoriel->add($tutoriel);
        }

        return $this;
    }

    public function removeTutoriel(Tutoriel $tutoriel): static
    {
        $this->tutoriel->removeElement($tutoriel);

        return $this;
    }
}
