<?php
// src/Repository/HistoriqueRepository.php
namespace App\Repository;

use App\Entity\Historique;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\Query;
use Doctrine\Persistence\ManagerRegistry;

class HistoriqueRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Historique::class);
    }

    public function createHistoriqueQuery(array $criteria = []): Query
    {
        $qb = $this->createQueryBuilder('h')
            ->leftJoin('h.utilisateur', 'u')
            ->addSelect('u')
            ->orderBy('h.dateHistorique', 'DESC');
    
        // Filtrer par type
        if (!empty($criteria['type'])) {
            $qb->andWhere('h.typeElement = :type')
               ->setParameter('type', $criteria['type']);
        }
    
        // Filtrer par date de début
        if (!empty($criteria['dateDebut'])) {
            $qb->andWhere('h.dateHistorique >= :dateDebut')
               ->setParameter('dateDebut', $criteria['dateDebut']);
        }
    
        // Filtrer par date de fin
        if (!empty($criteria['dateFin'])) {
            $qb->andWhere('h.dateHistorique <= :dateFin')
               ->setParameter('dateFin', $criteria['dateFin']);
        }
    
        return $qb->getQuery();
    }
    
    public function findUniqueTypes(): array
    {
        return $this->createQueryBuilder('h')
            ->select('DISTINCT h.typeElement')
            ->getQuery()
            ->getResult();
    }
 
    /**
     * Compte les entrées par type sur une période
     */
    public function countByTypeAndPeriod(\DateTime $startDate, \DateTime $endDate)
    {
        return $this->createQueryBuilder('h')
            ->select('h.typeElement, COUNT(h.id) as count')
            ->where('h.dateHistorique BETWEEN :startDate AND :endDate')
            ->setParameter('startDate', $startDate)
            ->setParameter('endDate', $endDate)
            ->groupBy('h.typeElement')
            ->getQuery()
            ->getResult();
    }

    /**
     * Trouve les dernières actions par utilisateur
     */
    public function findLastActionsByUser($userId, $limit = 5)
    {
        return $this->createQueryBuilder('h')
            ->where('h.utilisateur = :userId')
            ->setParameter('userId', $userId)
            ->orderBy('h.dateHistorique', 'DESC')
            ->setMaxResults($limit)
            ->getQuery()
            ->getResult();
    }
}