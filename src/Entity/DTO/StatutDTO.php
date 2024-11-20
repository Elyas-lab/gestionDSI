<?php

namespace App\Entity\DTO;

use App\Entity\Statut;

class StatutDTO
{
    // Constantes représentant les différents statuts
    public const STATUT_INITIE = 'initie';
    public const STATUT_EN_COURS = 'en_cours';
    public const STATUT_TRAITE = 'traite';
    public const STATUT_SUPPRIME = 'supprime';
    public const STATUT_ARCHIVE = 'archive';
    public const STATUT_DESACTIVE = 'desactive';
    public const STATUT_SUSPENDU = 'suspendu'; 

    /**
     * Obtenir la liste des statuts disponibles
     *
     * @return array
     */
    public static function getStatuts(): array
    {
        return [
            self::STATUT_INITIE => 'Initié',
            self::STATUT_EN_COURS => 'En cours',
            self::STATUT_TRAITE => 'Traité/Terminé',
            self::STATUT_SUPPRIME => 'Supprimé',
            self::STATUT_ARCHIVE => 'Archivé',
            self::STATUT_DESACTIVE => 'Désactivé',
            self::STATUT_SUSPENDU => 'Suspendu',  
        ];
    }
    
    // Autres méthodes et attributs...
     /**
     * Créer une liste d'instances Statut avec les valeurs par défaut.
     *
     * @return Statut[]
     */
    public static function initializeDefaultStatuts(): array
    {
        $defaultStatuts = [];
        foreach (self::getStatuts() as $key => $value) {
            $statut = new Statut();
            $statut->setTitre($key);
            $statut->setDescription($value);
            $defaultStatuts[] = $statut;
        }

        return $defaultStatuts;
    }
}
