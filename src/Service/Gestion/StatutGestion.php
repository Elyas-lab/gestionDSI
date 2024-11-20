<?php

namespace App\Service\Gestion;

use App\Entity\DTO\StatutDTO;
use App\Entity\Statut;
use Doctrine\ORM\EntityManagerInterface;

class StatutGestion
{
    private EntityManagerInterface $entityManager;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    public function getAvailableStatuts(): array
    {
        // Exemple avec Doctrine
        return $this->entityManager->getRepository(Statut::class)->findAll();
    }


    /**
     * Initialiser les statuts par défaut dans la base de données.
     *
     * @return void
     */
    public function initializeDefaultStatuts(): void
    {
        $statuts = StatutDTO::initializeDefaultStatuts();

        foreach ($statuts as $statut) {
            $this->entityManager->persist($statut);
        }

        $this->entityManager->flush();
    }

    /**
     * Ajouter un nouveau statut dans la base de données.
     *
     * @param string $titre
     * @param string $description
     * @return Statut
     */
    public function createStatut(string $titre, string $description): Statut
    {
        $statut = new Statut();
        $statut->setTitre($titre);
        $statut->setDescription($description);

        $this->entityManager->persist($statut);
        $this->entityManager->flush();

        return $statut;
    }

    /**
     * Récupérer un statut par son ID.
     *
     * @param int $id
     * @return Statut|null
     */
    public function getStatutById(int $id): ?Statut
    {
        return $this->entityManager->getRepository(Statut::class)->find($id);
    }

    /**
     * Mettre à jour un statut existant.
     *
     * @param Statut $statut
     * @param string|null $titre
     * @param string|null $description
     * @return Statut
     */
    public function updateStatut(Statut $statut, ?string $titre = null, ?string $description = null): Statut
    {
        if ($titre !== null) {
            $statut->setTitre($titre);
        }

        if ($description !== null) {
            $statut->setDescription($description);
        }

        $this->entityManager->flush();

        return $statut;
    }

    /**
     * Supprimer un statut.
     *
     * @param Statut $statut
     */
    public function deleteStatut(Statut $statut): void
    {
        $this->entityManager->remove($statut);
        $this->entityManager->flush();
    }

    /**
     * Valider si un statut donné est valide.
     *
     * @param string $statut
     * @return bool
     */
    public function isValidStatut(string $statut): bool
    {
        return array_key_exists($statut, StatutDTO::getStatuts());
    }
}
