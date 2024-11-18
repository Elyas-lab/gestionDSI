<?php

namespace App\Form;

use App\Entity\Canal;
use App\Entity\Demande;
use App\Entity\Demandeur;
use App\Entity\StatutDemande;
use App\Entity\Type;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class DemandeType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('titre')
            ->add('description')
            ->add('date_insertion', null, [
                'widget' => 'single_text',
            ])
            ->add('demandeur', EntityType::class, [
                'class' => Demandeur::class,
                'choice_label' => 'Entite_demandeur',
            ])
            ->add('type_demande', EntityType::class, [
                'class' => Type::class,
                'choice_label' => 'id',
            ])
            ->add('Canal_arrivage', EntityType::class, [
                'class' => Canal::class,
                'choice_label' => 'id',
            ])
            ->add('statut', EntityType::class, [
                'class' => StatutDemande::class,
                'choice_label' => 'id',
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Demande::class,
        ]);
    }
}
