<?php

namespace App\Entity;

use App\Repository\ProjetRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: ProjetRepository::class)]
class Projet
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $titre = null;

    #[ORM\Column(type: Types::TEXT, nullable: true)]
    private ?string $description = null;

    #[ORM\ManyToOne(inversedBy: 'projets')]
    private ?Utilisateur $chef_de_projet = null;

    /**
     * @var Collection<int, Utilisateur>
     */
    #[ORM\ManyToMany(targetEntity: Utilisateur::class, inversedBy: 'projet')]
    private Collection $ressource;

    #[ORM\Column]
    private ?\DateTimeImmutable $date_debut_estimmee = null;

    #[ORM\Column]
    private ?\DateTimeImmutable $date_fin_estimmee = null;

    #[ORM\Column(nullable: true)]
    private ?\DateTimeImmutable $date_debut_reel = null;

    #[ORM\Column(nullable: true)]
    private ?\DateTimeImmutable $date_fin_reel = null;

    /**
     * @var Collection<int, Activite>
     */
    #[ORM\OneToMany(targetEntity: Activite::class, mappedBy: 'projet_source')]
    private Collection $activites;

    #[ORM\ManyToOne(inversedBy: 'projets')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Statut $statut = null;

    public function __construct()
    {
        $this->ressource = new ArrayCollection();
        $this->activites = new ArrayCollection();
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

    public function setDescription(string $description): static
    {
        $this->description = $description;

        return $this;
    }

    public function getChefDeProjet(): ?Utilisateur
    {
        return $this->chef_de_projet;
    }

    public function setChefDeProjet(?Utilisateur $chef_de_projet): static
    {
        $this->chef_de_projet = $chef_de_projet;

        return $this;
    }

    /**
     * @return Collection<int, Utilisateur>
     */
    public function getRessource(): Collection
    {
        return $this->ressource;
    }

    public function addRessource(Utilisateur $ressource): static
    {
        if (!$this->ressource->contains($ressource)) {
            $this->ressource->add($ressource);
        }

        return $this;
    }

    public function removeRessource(Utilisateur $ressource): static
    {
        $this->ressource->removeElement($ressource);

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
     * @return Collection<int, Activite>
     */
    public function getActivites(): Collection
    {
        return $this->activites;
    }

    public function addActivite(Activite $activite): static
    {
        if (!$this->activites->contains($activite)) {
            $this->activites->add($activite);
            $activite->setProjetSource($this);
        }

        return $this;
    }

    public function removeActivite(Activite $activite): static
    {
        if ($this->activites->removeElement($activite)) {
            // set the owning side to null (unless already changed)
            if ($activite->getProjetSource() === $this) {
                $activite->setProjetSource(null);
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
