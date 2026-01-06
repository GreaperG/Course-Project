<?php

namespace App\Service;
// src/service/AccessManager.php

use App\Entity\Inventory;
use App\Entity\InventoryAccess;
use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface;

class AccessManager
{
    public function __construct(private EntityManagerInterface $em){}

    public function grantAccess(Inventory $inventory,User $user, string $permission = 'view'): void
    {
        $existing = $this->em->getRepository(InventoryAccess::class)
    ->findOneBy(['inventory' => $inventory, 'user' => $user]);


        if($existing){
            $existing->setPermission($permission);
        } else {
            $access = new InventoryAccess();
            $access->setInventory($inventory);
            $access->setUser($user);
            $access->setPermission($permission);
            $access->setGrantedAt(new \DateTime());

            $this->em->persist($access);
        }

        $this->em->flush();
    }

    public function revokeAccess(Inventory $inventory, User $user): void
    {
        $access = $this->em->getRepository(InventoryAccess::class)
            ->findOneBy(['inventory' => $inventory, 'user' => $user]);

        if($access){
            $this->em->remove($access);
            $this->em->flush();
        }
    }

    public function getUserAccesses(User $user): array
    {
        return $this->em->getRepository(InventoryAccess::class)
            ->findBy(['user' => $user]);
    }
}







?>
