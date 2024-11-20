<?php

namespace App\Service\Gestion;

use App\Entity\DTO\RoleDTO;
use App\Entity\Groupe;
use Doctrine\ORM\EntityManagerInterface;

class GroupeGestion
{
    private EntityManagerInterface $entityManager;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    /**
     * Initialize default groups if they don't exist
     * @return array Array of created groups
     */
    public function initializeDefaultGroups(): array
    {
        $defaultGroups = [
            RoleDTO::GROUPE_ADMIN => [
                'titre' => 'Administrateurs',
                'description' => 'Groupe des administrateurs avec tous les droits'
            ],
            RoleDTO::GROUPE_CHEF_PROJET => [
                'titre' => 'Chefs de projet',
                'description' => 'Groupe des chefs de projet'
            ],
            RoleDTO::GROUPE_UTILISATEUR => [
                'titre' => 'Utilisateurs',
                'description' => 'Groupe des utilisateurs standard'
            ]
        ];

        $createdGroups = [];
        
        foreach ($defaultGroups as $groupType => $groupData) {
            // Check if group already exists
            $existingGroup = $this->entityManager->getRepository(Groupe::class)
                ->findOneBy(['valeur' => $groupType]);
            
            if (!$existingGroup) {
                $groupe = new Groupe();
                $groupe->setTitre($groupData['titre'])
                    ->setDescription($groupData['description'])
                    ->setValeur($groupType);
                
                $this->entityManager->persist($groupe);
                $createdGroups[] = $groupe;
            }
        }
        
        if (!empty($createdGroups)) {
            $this->entityManager->flush();
        }
        
        return $createdGroups;
    }
}