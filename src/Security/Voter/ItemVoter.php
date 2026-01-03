<?php

namespace App\Security\Voter;

use App\Entity\Inventory;
use App\Entity\Item;
use App\Entity\User;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Authorization\Voter\Vote;
use Symfony\Component\Security\Core\Authorization\Voter\Voter;
final class ItemVoter extends Voter
{
    public const EDIT = 'EDIT';
    public const VIEW = 'VIEW';
    public const DELETE = 'DELETE';

    protected function supports(string $attribute, mixed $subject): bool
    {
        return in_array($attribute, [self::EDIT, self::VIEW, self::DELETE])
            && $subject instanceof Item;
    }

    protected function voteOnAttribute(string $attribute, mixed $subject, TokenInterface $token, ?Vote $vote = null): bool
    {
        $user = $token->getUser();

        // if the user is anonymous, do not grant access
        if (!$user instanceof User) {
            return $attribute === self::VIEW;
        }

        $item = $subject;
        $inventory = $item->getInventory();

        if (in_array('ROLE_ADMIN', $user->getRoles())) {
            return true;
        }

        return match($attribute) {
            self::EDIT => $this->canEdit($item,$inventory, $user),
            self::VIEW => $this->canView($item,$user),
            self::DELETE => $this->canDelete($item,$inventory, $user),
            default => false,
        };
    }


    private function canEdit(Item $item, Inventory $inventory, User $user): bool
    {
        if ($inventory->getOwner() === $user) {
            return true;
        }

        if ($item->getCreatedBy() === $user) {
            return true;
        }

        if($inventory->isPublic()){
            return true;
        }

        return false;
    }

    private function canView(Item $item, User $user): bool
    {
        return true;
    }

    private function canDelete(Item $item,Inventory $inventory,User $user): bool
    {
        return $inventory->getOwner() === $user || $item->getCreatedBy() === $user;
    }
}
