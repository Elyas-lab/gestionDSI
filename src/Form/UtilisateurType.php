<?php

namespace App\Form;

use App\Entity\Activite;
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
            ->add('roles')
            ->add('password')
            ->add('nom_utilisateur')
            ->add('projet', EntityType::class, [
                'class' => Projet::class,
                'choice_label' => 'id',
                'multiple' => true,
            ])
            ->add('activites', EntityType::class, [
                'class' => Activite::class,
                'choice_label' => 'id',
                'multiple' => true,
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Utilisateur::class,
        ]);
    }
}
