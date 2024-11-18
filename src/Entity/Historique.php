<?php

namespace App\Entity;

use App\Repository\HistoriqueRepository;
use Doctrine\ORM\Mapping as ORM;
use App\DTO\TypeElement;

#[ORM\Entity(repositoryClass: HistoriqueRepository::class)]
#[ORM\Table(name: 'historique')]
#[ORM\Index(name: 'idx_historique_type', columns: ['type_element', 'id_element'])]
#[ORM\Index(name: 'idx_historique_date', columns: ['date_historique'])]
class Historique
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    private ?int $id = null;

    #[ORM\Column(type: 'datetime')]
    private ?\DateTimeInterface $dateHistorique = null;

    #[ORM\Column(type: 'string', length: 1000, nullable: true)]
    private ?string $detailsHistorique = null;

    #[ORM\Column(type: 'string', enumType: TypeElement::class)]
    private ?TypeElement $typeElement = null;

    #[ORM\Column(type: 'integer')]
    private ?int $idElement = null;

    #[ORM\ManyToOne(targetEntity: Utilisateur::class)]
    #[ORM\JoinColumn(nullable: false)]
    private ?Utilisateur $utilisateur = null;

    public function __construct()
    {
        $this->dateHistorique = new \DateTime();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getDateHistorique(): ?\DateTimeInterface
    {
        return $this->dateHistorique;
    }

    public function setDateHistorique(\DateTimeInterface $dateHistorique): static
    {
        $this->dateHistorique = $dateHistorique;

        return $this;
    }

    public function getDetailsHistorique(): ?string
    {
        return $this->detailsHistorique;
    }

    public function setDetailsHistorique(?string $detailsHistorique): static
    {
        $this->detailsHistorique = $detailsHistorique;

        return $this;
    }

    public function getTypeElement(): ?TypeElement
    {
        return $this->typeElement;
    }

    public function setTypeElement(TypeElement $typeElement): static
    {
        $this->typeElement = $typeElement;

        return $this;
    }

    public function getIdElement(): ?int
    {
        return $this->idElement;
    }

    public function setIdElement(int $idElement): static
    {
        $this->idElement = $idElement;

        return $this;
    }

    public function getUtilisateur(): ?Utilisateur
    {
        return $this->utilisateur;
    }

    public function setUtilisateur(?Utilisateur $utilisateur): static
    {
        $this->utilisateur = $utilisateur;

        return $this;
    }
}