<?php

namespace App\Entity;

use App\Repository\AssistanceRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;


#[ORM\Entity(repositoryClass: AssistanceRepository::class)]
class Assistance
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $titre = null;

    #[ORM\Column(type: Types::TEXT, nullable: true)]
    private ?string $description = null;

    #[ORM\Column]
    private ?\DateTimeImmutable $date_insertion = null;

    #[ORM\ManyToOne(inversedBy: 'demandes')]
    private ?Demandeur $demandeur = null;

    #[ORM\ManyToOne(inversedBy: 'assistances')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Statut $statut = null;

    public function getId(): ?int
    {
        return $this->id;
    }
    public function getTitre():?string {
        return $this->titre;
    }
    public function setTitre(?string $titre): self {
        $this->titre = $titre;
        return $this;
    }
    public function getDescription():?string {
        return $this->description;
    }
    public function setDescription(?string $description): self {
        $this->description = $description;
        return $this;
    }

    public function getStatut(): ?Statut
    {
        return $this->statut;
    }

    public function setStatut(?Statut $statut): static
    {
        $this->statut = $statut;

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

}
