<?php

namespace App\Entity;

use App\Entity\DTO\RoleDTO;
use App\Repository\GroupeRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: GroupeRepository::class)]
class Groupe
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $titre = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $description = null;

    #[ORM\ManyToMany(targetEntity: Utilisateur::class, inversedBy: 'groupes')]
    private Collection $membres;

    #[ORM\Column(length: 255)]
    private ?string $valeur = null;

    public function __construct()
    {
        $this->membres = new ArrayCollection();
    }

    public function getId():?int
    {   return $this->id; }
    public function getTitre():?string
    {   return $this->titre; }
    public function setTitre(string $titre): static{ 
        $this->titre = $titre;
        return $this;  
    }
    public function getDescription():?string{   return $this->description; }
    public function setDescription(string $description): static{
        $this->description = $description;
        return $this;
    }
    public function getMembres(): Collection{   return $this->membres; }

    public function getValeur():?string{   return $this->valeur; }

    public function setValeur(string $valeur): static
    {
        if (!RoleDTO::isGroupeValid($valeur)) {
            throw new \InvalidArgumentException('Type de groupe invalide');
        }

        $this->valeur = $valeur;
        return $this;
    }

    public function getRolesByGroupe(): array
    {
        return RoleDTO::getRolesByGroupe($this->valeur);
    }

    public function addMembre(Utilisateur $membre): static
    {
        if (!$this->membres->contains($membre)) {
            $this->membres->add($membre);
            // Met à jour les rôles de l'utilisateur
            $membre->setRoles(array_unique(array_merge(
                $membre->getRoles(),
                $this->getRolesByGroupe()
            )));
        }

        return $this;
    }

    public function removeMembre(Utilisateur $membre): static
    {
        if ($this->membres->removeElement($membre)) {
            // Retire les rôles associés au groupe
            $membre->setRoles(array_diff(
                $membre->getRoles(),
                $this->getRolesByGroupe()
            ));
        }

        return $this;
    }
}