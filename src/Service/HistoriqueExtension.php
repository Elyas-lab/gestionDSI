<?php

namespace App\Service;

use App\Entity\DTO\TypeElement;
use Twig\Extension\AbstractExtension;
use Twig\TwigFunction;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;

class HistoriqueExtension extends AbstractExtension
{
    private $urlGenerator;

    public function __construct(UrlGeneratorInterface $urlGenerator)
    {
        $this->urlGenerator = $urlGenerator;
    }

    public function getFunctions(): array
    {
        return [
            new TwigFunction('element_link', [$this, 'getElementLink']),
        ];
    }

    public function getElementLink(string $type, int $id): ?array
    {
        return match ($type) {
            TypeElement::Activite->value => [
                'url' => $this->urlGenerator->generate('app_activite_show', ['id' => $id]),
                'label' => "Activité #$id",
                
            ],
            TypeElement::Assistance->value => [
                'url' => $this->urlGenerator->generate('app_assistance_show', ['id' => $id]),
                'label' => "Assistance #$id",
                
            ],
            TypeElement::Demande->value => [
                'url' => $this->urlGenerator->generate('app_demande_show', ['id' => $id]),
                'label' => "Demande #$id",
                
            ],
            TypeElement::Projet->value => [
                'url' => $this->urlGenerator->generate('app_projet_show', ['id' => $id]),
                'label' => "Projet #$id",
                
            ],
            TypeElement::Tache->value => [
                'url' => $this->urlGenerator->generate('app_tache_show', ['id' => $id]),
                'label' => "Tâche #$id",
           ],
            TypeElement::Utilisateur->value => [
                'url' => $this->urlGenerator->generate('app_utilisateur_show', ['id' => $id]),
                'label' => "Utilisateur #$id",
            ],
            default => null
        };
    }
    
}