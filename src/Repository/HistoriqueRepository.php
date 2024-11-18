<?php
// src/Repository/HistoriqueRepository.php
namespace App\Repository;

use App\Entity\Historique;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

class HistoriqueRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Historique::class);
    }

    /**
     * Récupère l'historique avec pagination
     */
    public function findHistoriqueWithPagination(array $criteria = [], int $page = 1, int $limit = 10)
    {
        $qb = $this->createQueryBuilder('h')
            ->leftJoin('h.utilisateur', 'u')
            ->addSelect('u');

        // Applique les critères de recherche
        foreach ($criteria as $field => $value) {
            if ($value !== null) {
                $qb->andWhere("h.$field = :$field")
                   ->setParameter($field, $value);
            }
        }

        return $qb->orderBy('h.dateHistorique', 'DESC')
            ->setFirstResult(($page - 1) * $limit)
            ->setMaxResults($limit)
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