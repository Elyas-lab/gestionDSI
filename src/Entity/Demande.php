<?php

namespace App\Entity;

use App\Repository\DemandeRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: DemandeRepository::class)]
class Demande
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $titre = null;

    #[ORM\Column(length: 255)]
    private ?string $description = null;

    #[ORM\Column]
    private ?\DateTimeImmutable $date_insertion = null;

    #[ORM\ManyToOne(inversedBy: 'demandes')]
    private ?Demandeur $demandeur = null;

    #[ORM\ManyToOne(inversedBy: 'demandes')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Type $type_demande = null;

    #[ORM\ManyToOne(inversedBy: 'demandes')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Canal $Canal_arrivage = null;

    #[ORM\ManyToOne(inversedBy: 'demandes')]
    #[ORM\JoinColumn(nullable: false)]
    private ?StatutDemande $statut = null;

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

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(string $description): static
    {
        $this->description = $description;

        return $this;
    }

    public function getDateInsertion(): ?\DateTimeImmutable
    {
        return $this->date_insertion;
    }

    public function setDateInsertion(\DateTimeImmutable $date_insertion): static
    {
        $this->date_insertion = $date_insertion;

        return $this;
    }

    public function getDemandeur(): ?Demandeur
    {
        return $this->demandeur;
    }

    public function setDemandeur(?Demandeur $demandeur): static
    {
        $this->demandeur = $demandeur;

        return $this;
    }

    public function getTypeDemande(): ?Type
    {
        return $this->type_demande;
    }

    public function setTypeDemande(?Type $type_demande): static
    {
        $this->type_demande = $type_demande;

        return $this;
    }

    public function getCanalArrivage(): ?Canal
    {
        return $this->Canal_arrivage;
    }

    public function setCanalArrivage(?Canal $Canal_arrivage): static
    {
        $this->Canal_arrivage = $Canal_arrivage;

        return $this;
    }

    public function getStatut(): ?StatutDemande
    {
        return $this->statut;
    }

    public function setStatut(?StatutDemande $statut): static
    {
        $this->statut = $statut;

        return $this;
    }
}
