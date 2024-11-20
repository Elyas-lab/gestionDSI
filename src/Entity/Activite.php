<?php

namespace App\Entity;

use App\Repository\ActiviteRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: ActiviteRepository::class)]
class Activite
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
    private Collection $responsables;

    #[ORM\ManyToOne(inversedBy: 'activites')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Projet $projet_source = null;

    #[ORM\Column]
    private ?\DateTimeImmutable $date_debut_estimmee = null;

    #[ORM\Column]
    private ?\DateTimeImmutable $date_fin_estimmee = null;

    #[ORM\Column(nullable: true)]
    private ?\DateTimeImmutable $date_debut_reel = null;

    #[ORM\Column(nullable: true)]
    private ?\DateTimeImmutable $date_fin_reel = null;

    /**
     * @var Collection<int, Tache>
     */
    #[ORM\OneToMany(targetEntity: Tache::class, mappedBy: 'activite_source')]
    private Collection $taches;

    #[ORM\ManyToOne(inversedBy: 'activites')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Statut $statut = null;

    public function __construct()
    {
        $this->responsables = new ArrayCollection();
        $this->taches = new ArrayCollection();
    }

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

    public function setDescription(?string $description): static
    {
        $this->description = $description;

        return $this;
    }

    /**
     * @return Collection<int, Utilisateur>
     */
    public function getResponsable(): Collection
    {
        return $this->responsables;
    }

    public function addResponsable(Utilisateur $responsable): static
    {
        if (!$this->responsables->contains($responsable)) {
            $this->responsables->add($responsable);
        }

        return $this;
    }

    public function removeResponsable(Utilisateur $responsable): static
    {
        $this->responsables->removeElement($responsable);

        return $this;
    }

    public function getProjetSource(): ?Projet
    {
        return $this->projet_source;
    }

    public function setProjetSource(?Projet $projet_source): static
    {
        $this->projet_source = $projet_source;

        return $this;
    }

    public function getDateDebutEstimmee(): ?\DateTimeImmutable
    {
        return $this->date_debut_estimmee;
    }

    public function setDateDebutEstimmee(\DateTimeImmutable $date_debut_estimmee): static
    {
        $this->date_debut_estimmee = $date_debut_estimmee;

        return $this;
    }

    public function getDateFinEstimmee(): ?\DateTimeImmutable
    {
        return $this->date_fin_estimmee;
    }

    public function setDateFinEstimmee(\DateTimeImmutable $date_fin_estimmee): static
    {
        $this->date_fin_estimmee = $date_fin_estimmee;

        return $this;
    }

    public function getDateDebutReel(): ?\DateTimeImmutable
    {
        return $this->date_debut_reel;
    }

    public function setDateDebutReel(?\DateTimeImmutable $date_debut_reel): static
    {
        $this->date_debut_reel = $date_debut_reel;

        return $this;
    }

    public function getDateFinReel(): ?\DateTimeImmutable
    {
        return $this->date_fin_reel;
    }

    public function setDateFinReel(?\DateTimeImmutable $date_fin_reel): static
    {
        $this->date_fin_reel = $date_fin_reel;

        return $this;
    }

    /**
     * @return Collection<int, Tache>
     */
    public function getTaches(): Collection
    {
        return $this->taches;
    }

    public function addTach(Tache $tach): static
    {
        if (!$this->taches->contains($tach)) {
            $this->taches->add($tach);
            $tach->setActiviteSource($this);
        }

        return $this;
    }

    public function removeTach(Tache $tach): static
    {
        if ($this->taches->removeElement($tach)) {
            // set the owning side to null (unless already changed)
            if ($tach->getActiviteSource() === $this) {
                $tach->setActiviteSource(null);
            }
        }

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
