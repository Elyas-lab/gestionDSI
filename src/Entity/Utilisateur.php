<?php
namespace App\Entity;

use App\Entity\DTO\RoleDTO;
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
    private Collection $activites;

    public function __construct()
    {
        // Initialisation des collections
        $this->groupes = new ArrayCollection();
        $this->projetsDiriges = new ArrayCollection();
        $this->projetsParticipes = new ArrayCollection();
        $this->activites = new ArrayCollection();

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

        // Ajouter des rôles supplémentaires en fonction des groupes de l'utilisateur
        foreach ($this->groupes as $groupe) {
            // Exemple d'ajout de rôle en fonction du groupe (si vous avez une logique à ce sujet)
            $roles[] = $groupe->getValeur(); // Assurez-vous que 'getValeur()' retourne un rôle valide
        }

        return array_unique($roles);
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

    /**
     * Add a group to the user
     * @param Groupe $groupe
     * @return $this
     */
    public function addGroupe(Groupe $groupe): static
    {
        // Check if the group is already added
        if (!$this->groupes->contains($groupe)) {
            // Add the group to the user's collection of groups
            $this->groupes->add($groupe);

            // Ensure the inverse side of the relation is also updated
            if (!$groupe->getMembres()->contains($this)) {
                $groupe->addMembre($this); // Assuming the Groupe entity has an `addMembre()` method
            }
        }

        return $this;
    }
    public function removeGroupe(Groupe $groupe): static
    {
        if ($this->groupes->removeElement($groupe)) {
            // Remove the user from the group's members if needed
            // Optionally, you can add extra logic here to handle any cleanup or role changes.
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
    public function getActivites(): Collection
    {
        return $this->activites;
    }

    public function hasRole(string $role): bool
    {
        return in_array($role, $this->getRoles());
    }
}
