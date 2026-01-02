<?php

namespace App\Security\Voter;

use App\Entity\Inventory;
use App\Entity\User;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Authorization\Voter\Vote;
use Symfony\Component\Security\Core\Authorization\Voter\Voter;
use Symfony\Component\Security\Core\User\UserInterface;


final class InventoryVoter extends Voter
{
    public const EDIT = 'EDIT';
    public const DELETE = 'DELETE';
    public const VIEW = 'VIEW';
    public const MANAGE_ACCESS = 'MANAGE_ACCESS';

    protected function supports(string $attribute, mixed $subject): bool
    {
        // replace with your own logic
        // https://symfony.com/doc/current/security/voters.html
        if (!in_array($attribute, [self::EDIT, self::VIEW, self::MANAGE_ACCESS, self::DELETE])) {
            return false;
        }

        if (!$subject instanceof Inventory) {
            return false;
        }
        return true;

    }

    protected function voteOnAttribute(string $attribute, mixed $subject, TokenInterface $token, ?Vote $vote = null): bool
    {
        $user = $token->getUser();

        // if the user is anonymous, do not grant access
        if (!$user instanceof UserInterface) {
            return false;
        }


        $inventory = $subject;

        if (in_array('ROLE_ADMIN', $user->getRoles())){
            return true;
        }

        return match($attribute) {
        self::VIEW => $this->canView($inventory, $user),
        self::EDIT => $this->canEdit($inventory, $user),
        self::DELETE => $this->canDelete($inventory, $user),
        self::MANAGE_ACCESS => $this->canManageAccess($inventory, $user),
        default => false,
        };
}
        public function canView(Inventory $inventory, User $user): bool
        {
            return true;
        }
        public function canEdit(Inventory $inventory, User $user): bool
        {
            return true;
        }

        private function canDelete(Inventory $inventory, User $user): bool
        {
            return $inventory->getOwner() === $user;
        }

        private function canManageAccess(Inventory $inventory, User $user): bool
        {
            return $inventory->getOwner() === $user;

        }








}
