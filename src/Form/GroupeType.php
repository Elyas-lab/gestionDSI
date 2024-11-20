<?php

namespace App\Form;

use App\Entity\DTO\RoleDTO;
use App\Entity\Groupe;
use App\Entity\Utilisateur;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\NotBlank;

class GroupeType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            // Titre du groupe avec contrainte de validation
            ->add('titre', TextType::class, [
                'label' => 'Titre du groupe',
                'constraints' => [
                    new NotBlank(['message' => 'Veuillez saisir un titre pour le groupe'])
                ],
                'attr' => [
                    'class' => 'form-control',
                    'placeholder' => 'Saisissez le titre du groupe'
                ]
            ])
            
            // Description avec TextArea
            ->add('description', TextareaType::class, [
                'label' => 'Description du groupe',
                'required' => false,
                'attr' => [
                    'class' => 'form-control',
                    'rows' => 3,
                    'placeholder' => 'Description optionnelle du groupe'
                ]
            ])
            
            // Valeur du groupe avec un select des types de groupes prédéfinis
            ->add('valeur', ChoiceType::class, [
                'label' => 'Type de groupe',
                'choices' => array_combine(
                    [
                        'Administrateur', 
                        'Manager', 
                        'Chef de Projet', 
                        'Utilisateur Standard'
                    ],
                    [
                        RoleDTO::GROUPE_ADMIN, 
                        RoleDTO::GROUPE_MANAGER, 
                        RoleDTO::GROUPE_CHEF_PROJET, 
                        RoleDTO::GROUPE_UTILISATEUR
                    ]
                ),
                'placeholder' => 'Sélectionnez un type de groupe',
                'constraints' => [
                    new NotBlank(['message' => 'Veuillez sélectionner un type de groupe'])
                ],
                'attr' => [
                    'class' => 'form-control'
                ]
            ])
            
            // Membres avec des options améliorées
            ->add('membres', EntityType::class, [
                'class' => Utilisateur::class,
                'label' => 'Membres du groupe',
                'choice_label' => function(Utilisateur $utilisateur) {
                    return sprintf(' %s ',  
                        $utilisateur->getNom()
                    );
                },
                'multiple' => true,
                'expanded' => true, // Affiche des cases à cocher
                'required' => false,
                'attr' => [
                    'class' => 'form-check'
                ]
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Groupe::class,
            'translation_domain' => 'forms', // Pour l'internationalisation
            'allow_extra_fields' => true, // Pour permettre des champs supplémentaires si nécessaire
        ]);
    }

    // Méthode pour personnaliser le nom du formulaire
    public function getBlockPrefix(): string
    {
        return 'groupe';
    }
}