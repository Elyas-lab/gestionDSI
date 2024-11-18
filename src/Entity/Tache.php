<?php

namespace App\Entity;

use App\Repository\TacheRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: TacheRepository::class)]
class Tache
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $titre = null;

    #[ORM\Column(type: Types::TEXT, nullable: true)]
    private ?string $description = null;

    /**
     * @var Collection<int, Utilisateur>
     */
    #[ORM\ManyToMany(targetEntity: Utilisateur::class, inversedBy: 'activites')]
    private Collection $responsable;

    #[ORM\Column]
    private ?\DateTimeImmutable $date_debut_estimmee = null;

    #[ORM\Column]
    private ?\DateTimeImmutable $date_fin_estimmee = null;

    #[ORM\Column(nullable: true)]
    private ?\DateTimeImmutable $date_debut_reel = null;

    #[ORM\Column(nullable: true)]
    private ?\DateTimeImmutable $date_fin_reel = null;

    #[ORM\ManyToOne(inversedBy: 'taches')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Activite $activite_source = null;

    #[ORM\ManyToOne(inversedBy: 'taches')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Statut $statut = null;

    public function __construct() {
        $this->responsable = new ArrayCollection();
    }
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
    /**
     * @return Collection<int, Utilisateur>
     */
    public function getResponsable(): Collection {
        return $this->responsable;
    }
    public function addResponsable(Utilisateur $responsable): self {
        if (!$this->responsable->contains($responsable)) {
            $this->responsable[] = $responsable;
        }
        return $this;
    }
    public function removeResponsable(Utilisateur $responsable): self {
        $this->responsable->removeElement($responsable);
        return $this;
    }
    public function getDateDebutEstimmee():?\DateTimeImmutable {
        return $this->date_debut_estimmee;
    }
    public function setDateDebutEstimmee(?\DateTimeImmutable $date_debut_estimmee): self {
        $this->date_debut_estimmee = $date_debut_estimmee;
        return $this;
    }
    public function getDateFinEstimmee():?\DateTimeImmutable {
        return $this->date_fin_estimmee;
    }
    public function setDateFinEstimmee(?\DateTimeImmutable $date_fin_estimmee): self {
        $this->date_fin_estimmee = $date_fin_estimmee;
        return $this;
    }
    public function getDateDebutReel():?\DateTimeImmutable {
        return $this->date_debut_reel;
    }
    public function setDateDebutReel(?\DateTimeImmutable $date_debut_reel): self {
        $this->date_debut_reel = $date_debut_reel;
        return $this;
    }
    public function getDateFinReel():?\DateTimeImmutable {
        return $this->date_fin_reel;
    }
    public function setDateFinReel(?\DateTimeImmutable $date_fin_reel): self {
        $this->date_fin_reel = $date_fin_reel;
        return $this;
    }

    public function getActiviteSource(): ?Activite
    {
        return $this->activite_source;
    }

    public function setActiviteSource(?Activite $activite_source): static
    {
        $this->activite_source = $activite_source;

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

}
