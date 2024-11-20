<?php

namespace App\Entity\DTO;

class RoleDTO
{
    // Types de groupes
    public const GROUPE_ADMIN = 'ADMIN';
    public const GROUPE_CHEF_PROJET = 'CHEF_PROJET';
    public const GROUPE_UTILISATEUR = 'UTILISATEUR';

    // Rôles correspondants aux groupes
    public const ROLE_ADMIN = 'ROLE_ADMIN';
    public const ROLE_CHEF_PROJET = 'ROLE_CHEF_PROJET';
    public const ROLE_USER = 'ROLE_USER';

    // Mapping des groupes vers les rôles
    public const GROUPE_ROLES_MAP = [
        self::GROUPE_ADMIN => [
            self::ROLE_ADMIN,
            self::ROLE_CHEF_PROJET,
            self::ROLE_USER
        ],
        self::GROUPE_CHEF_PROJET => [
            self::ROLE_CHEF_PROJET,
            self::ROLE_USER
        ],
        self::GROUPE_UTILISATEUR => [
            self::ROLE_USER
        ],
    ];

    // Liste des groupes valides
    public const GROUPES_VALIDES = [
        self::GROUPE_ADMIN,
        self::GROUPE_CHEF_PROJET,
        self::GROUPE_UTILISATEUR
    ];

    /**
     * Retourne les rôles correspondant à un type de groupe
     */
    public static function getRolesByGroupe(string $groupeType): array
    {
        return self::GROUPE_ROLES_MAP[$groupeType] ?? [self::ROLE_USER];
    }

    /**
     * Vérifie si le type de groupe est valide
     */
    public static function isGroupeValid(string $groupeType): bool
    {
        return in_array($groupeType, self::GROUPES_VALIDES);
    }
}