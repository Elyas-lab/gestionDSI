<?php

namespace App\Entity;

use App\DTO\RoleDTO;
use App\Repository\UtilisateurRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Security\Core\User\PasswordAuthenticatedUserInterface;
use Symfony\Component\Security\Core\User\UserInterface;

#[ORM\Entity(repositoryClass: UtilisateurRepository::class)]
#[ORM\UniqueConstraint(name: 'UNIQ_IDENTIFIER_MATRICULE', fields: ['matricule'])]
class Utilisateur implements UserInterface, PasswordAuthenticatedUserInterface
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 180)]
    private ?string $matricule = null;

    #[ORM\Column(length: 255)]
    private ?string $nom = null;

    #[ORM\Column(length: 255)]
    private ?string $prenom = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $email = null;

    /**
     * @var list<string> Les rôles de l'utilisateur
     */
    #[ORM\Column]
    private array $roles = [];

    /**
     * Mot de passe haché
     */
    #[ORM\Column]
    private ?string $password = null;

    /**
     * Mot de passe en clair temporaire (non stocké en base)
     */
    private ?string $plainPassword = null;

    /**
     * Groupes auxquels l'utilisateur appartient
     * @var Collection<int, Groupe>
     */
    #[ORM\ManyToMany(targetEntity: Groupe::class, mappedBy: 'membres')]
    private Collection $groupes;

    /**
     * Projets dont l'utilisateur est le chef de projet
     * @var Collection<int, Projet>
     */
    #[ORM\OneToMany(targetEntity: Projet::class, mappedBy: 'chefDeProjet')]
    private Collection $projetsDiriges;

    /**
     * Projets auxquels l'utilisateur participe
     * @var Collection<int, Projet>
     */
    #[ORM\ManyToMany(targetEntity: Projet::class, mappedBy: 'ressources')]
    private Collection $projetsParticipes;

    /**
     * Activités dont l'utilisateur est responsable
     * @var Collection<int, Activite>
     */
    #[ORM\ManyToMany(targetEntity: Activite::class, mappedBy: 'responsables')]
    private Collection $activitesResponsables;

    public function __construct()
    {
        // Initialisation des collections
        $this->groupes = new ArrayCollection();
        $this->projetsDiriges = new ArrayCollection();
        $this->projetsParticipes = new ArrayCollection();
        $this->activitesResponsables = new ArrayCollection();

        // Rôle par défaut
        $this->roles = [RoleDTO::ROLE_USER];
    }

    /**
     * Getters et Setters
     */
    public function getId(): ?int
    {
        return $this->id;
    }

    public function getMatricule(): ?string
    {
        return $this->matricule;
    }

    public function setMatricule(string $matricule): static
    {
        $this->matricule = $matricule;
        return $this;
    }

    public function getNom(): ?string
    {
        return $this->nom;
    }

    public function setNom(string $nom): static
    {
        $this->nom = $nom;
        return $this;
    }

    public function getPrenom(): ?string
    {
        return $this->prenom;
    }

    public function setPrenom(string $prenom): static
    {
        $this->prenom = $prenom;
        return $this;
    }

    public function getEmail(): ?string
    {
        return $this->email;
    }

    public function setEmail(?string $email): static
    {
        $this->email = $email;
        return $this;
    }

    /**
     * Méthodes de UserInterface
     */
    public function getUserIdentifier(): string
    {
        return (string) $this->matricule;
    }

    public function getRoles(): array
    {
        // Garantit que chaque utilisateur a au moins ROLE_USER
        $roles = $this->roles;
        $roles[] = RoleDTO::ROLE_USER;
        
        return array_unique($roles);
    }

    public function setRoles(array $roles): static
    {
        $this->roles = $roles;
        return $this;
    }

    /**
     * Gestion du mot de passe
     */
    public function getPassword(): ?string
    {
        return $this->password;
    }

    public function setPassword(string $password): static
    {
        $this->password = $password;
        return $this;
    }

    public function getPlainPassword(): ?string
    {
        return $this->plainPassword;
    }

    public function setPlainPassword(?string $plainPassword): static
    {
        $this->plainPassword = $plainPassword;
        return $this;
    }

    public function eraseCredentials(): void
    {
        // Efface le mot de passe en clair
        $this->plainPassword = null;
    }

    /**
     * Gestion des Groupes
     * @return Collection<int, Groupe>
     */
    public function getGroupes(): Collection
    {
        return $this->groupes;
    }

    public function addGroupe(Groupe $groupe): static
    {
        if (!$this->groupes->contains($groupe)) {
            $this->groupes->add($groupe);
            
            // Mise à jour des rôles
            $this->setRoles(array_unique(array_merge(
                $this->getRoles(),
                $groupe->getRolesByGroupe()
            )));
        }

        return $this;
    }

    public function removeGroupe(Groupe $groupe): static
    {
        if ($this->groupes->removeElement($groupe)) {
            // Mise à jour des rôles en retirant les rôles du groupe
            $this->setRoles(array_diff(
                $this->getRoles(),
                $groupe->getRolesByGroupe()
            ));
        }

        return $this;
    }

    /**
     * Gestion des Projets
     * @return Collection<int, Projet>
     */
    public function getProjetsDiriges(): Collection
    {
        return $this->projetsDiriges;
    }

    /**
     * @return Collection<int, Projet>
     */
    public function getProjetsParticipes(): Collection
    {
        return $this->projetsParticipes;
    }

    /**
     * Gestion des Activités
     * @return Collection<int, Activite>
     */
    public function getActivitesResponsables(): Collection
    {
        return $this->activitesResponsables;
    }

    /**
     * Méthodes utilitaires
     */
    public function getNomComplet(): string
    {
        return sprintf('%s %s', $this->prenom, $this->nom);
    }

    public function hasRole(string $role): bool
    {
        return in_array($role, $this->getRoles());
    }

    public function __toString(): string
    {
        return $this->getNomComplet();
    }
}