<?php

namespace App\Form;

use App\Entity\Activite;
use App\Entity\DTO\RoleDTO;
use App\Entity\Projet;
use App\Entity\Utilisateur;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class UtilisateurType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('matricule')
            ->add('nom')
            ->add('roles')
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
            ->add('roles', ChoiceType::class, [
                'choices' => [
                    'Admin' => RoleDTO::GROUPE_ADMIN,
                    'Manager' => RoleDTO::GROUPE_MANAGER,
                    'Chef de Projet' => RoleDTO::GROUPE_CHEF_PROJET,
                    'Utilisateur' => RoleDTO::GROUPE_UTILISATEUR,
                ],
                'expanded' => true,  // Utilise des cases à cocher ou des boutons radio
                'multiple' => true, // Si vous voulez une seule sélection
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
