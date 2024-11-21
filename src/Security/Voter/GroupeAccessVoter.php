<?php
namespace App\Security\Voter;

use App\Entity\DTO\RoleDTO;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Authorization\Voter\Voter;

class GroupeAccessVoter extends Voter
{
    protected function supports(string $attribute, $subject): bool
    {
        // Vérifie si l'attribut correspond à un groupe connu
        return in_array($attribute, RoleDTO::GROUPES_VALIDES);
    }

    protected function voteOnAttribute(string $attribute, $subject, TokenInterface $token): bool
    {
        $user = $token->getUser();

        if (!$user || !method_exists($user, 'getRoles')) {
            return false;
        }

        $roles = $user->getRoles();
        $groupeRoles = RoleDTO::getRolesByGroupe($attribute);

        // Autorise si l'utilisateur a au moins un rôle correspondant au groupe
        return !empty(array_intersect($roles, $groupeRoles));
    }
}
