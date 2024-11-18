<?php
// src/Service/HistoriqueService.php
namespace App\Service;

use App\Entity\Historique;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\SecurityBundle\Security;
use App\DTO\TypeElement;
use DateTime;

class HistoriqueService
{
    private $entityManager;
    private $security;

    public function __construct(
        EntityManagerInterface $entityManager,
        Security $security
    ) {
        $this->entityManager = $entityManager;
        $this->security = $security;
    }

    /**
     * Ajoute une entrée dans l'historique
     */
    public function addHistorique(
        TypeElement $typeElement,
        int $idElement,
        string $details,
        ?\DateTime $date = null
    ): Historique {
        $historique = new Historique();
        $historique->setTypeElement($typeElement);
        $historique->setIdElement($idElement);
        $historique->setDetailsHistorique($details);
        
        // Use the provided date or current date if null
        $historique->setDateHistorique($date ?? new \DateTime());
    
        $utilisateur = $this->security->getUser();
        if ($utilisateur) {
            $historique->setUtilisateur($utilisateur);
        }
    
        $this->entityManager->persist($historique);
        $this->entityManager->flush();
    
        return $historique;
    }
    

    /**
     * Récupère l'historique pour un élément spécifique
     */
    public function getHistorique(
        string $typeElement,
        int $idElement,
        int $limit = 10
    ): array {
        return $this->entityManager
            ->getRepository(Historique::class)
            ->findBy(
                ['typeElement' => $typeElement, 'idElement' => $idElement],
                ['dateHistorique' => 'DESC'],
                $limit
            );
    }

    /**
     * Recherche dans l'historique avec des critères multiples
     */
    public function searchHistorique(array $criteria, array $orderBy = null, $limit = null, $offset = null): array
    {
        return $this->entityManager
            ->getRepository(Historique::class)
            ->findBy($criteria, $orderBy, $limit, $offset);
    }
}