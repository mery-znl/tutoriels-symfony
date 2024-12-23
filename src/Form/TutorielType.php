<?php

namespace App\Form;

use App\Entity\Chapitre;
use App\Entity\Matiere;
use App\Entity\Tutoriel;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class TutorielType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('titre')
            ->add('contenu')
            ->add('description')
            ->add('matiere', EntityType::class, [
                'class' => Matiere::class,
                'choice_label' => 'id',
            ])
            ->add('chapitres', EntityType::class, [
                'class' => Chapitre::class,
                'choice_label' => 'id',
                'multiple' => true,
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Tutoriel::class,
        ]);
    }
}
