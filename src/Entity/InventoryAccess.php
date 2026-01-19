<?php

namespace App\Entity;

use App\Repository\InventoryAccessRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: InventoryAccessRepository::class)]
class InventoryAccess
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\ManyToOne(targetEntity: Inventory::class, inversedBy: 'inventoryAccesses')]
    #[ORM\JoinColumn(nullable: false, onDelete: 'CASCADE')]
    private ?Inventory $inventory = null;

    #[ORM\ManyToOne(targetEntity: User::class, inversedBy: 'inventoryAccesses')]
    #[ORM\JoinColumn(nullable: false)]
    private ?User $user = null;

    #[ORM\Column(length: 20)]
    private ?string $permission = 'view';

    #[ORM\Column(nullable: true)]
    private ?\DateTime $grantedAt = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getInventory(): ?Inventory
    {
        return $this->inventory;
    }

    public function setInventory(?Inventory $inventory): static
    {
        $this->inventory = $inventory;

        return $this;
    }

    public function getUser(): ?User
    {
        return $this->user;
    }

    public function setUser(?User $user): static
    {
        $this->user = $user;

        return $this;
    }

    public function getPermission(): ?string
    {
        return $this->permission;
    }

    public function setPermission(string $permission): static
    {
        $this->permission = $permission;

        return $this;
    }

    public function getGrantedAt(): ?\DateTime
    {
        return $this->grantedAt;
    }

    public function setGrantedAt(?\DateTime $grantedAt): static
    {
        $this->grantedAt = $grantedAt;

        return $this;
    }
}
