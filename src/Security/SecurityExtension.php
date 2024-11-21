<?php
namespace App\Security;

use App\Entity\DTO\RoleDTO;
use Symfony\Bundle\SecurityBundle\Security;
use Twig\Extension\AbstractExtension;
use Twig\TwigFunction;

class SecurityExtension extends AbstractExtension
{
    private Security $security;

    public function __construct(Security $security)
    {
        $this->security = $security;
    }

    public function getFunctions(): array
    {
        return [
            new TwigFunction('has_groupe_access', [$this, 'hasGroupeAccess']),
        ];
    }

    public function hasGroupeAccess(string $groupeType): bool
    {
        $user = $this->security->getUser();

        if (!$user || !method_exists($user, 'getRoles')) {
            return false;
        }

        $roles = $user->getRoles();
        $groupeRoles = RoleDTO::getRolesByGroupe($groupeType);

        // Vérifie si l'utilisateur a au moins un rôle dans le groupe
        return !empty(array_intersect($roles, $groupeRoles));
    }
}
