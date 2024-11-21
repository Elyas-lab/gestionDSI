<?php

namespace App\Form;

use App\Entity\Activite;
use App\Entity\Groupe;
use App\Entity\Projet;
use App\Entity\Utilisateur;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class UtilisateurType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('matricule')
            ->add('nom')
            ->add('projetsParticipes', EntityType::class, [
                'class' => Projet::class,
                'choice_label' => 'titre',
                'multiple' => true,
                'required' => false,
            ])
            ->add('activites', EntityType::class, [
                'class' => Activite::class,
                'choice_label' => 'titre',
                'multiple' => true,
                'required' => false,
            ])
            ->add('groupes', EntityType::class, [
                'class' => Groupe::class,
                'choice_label' => 'titre', 
                'expanded' => true,     // Utilise des cases à cocher
                'multiple' => true,     // Autorise la sélection multiple
                'by_reference' => false // Important pour les relations ManyToMany
            ]);
        ;
}
    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Utilisateur::class,
        ]);
    }
}
