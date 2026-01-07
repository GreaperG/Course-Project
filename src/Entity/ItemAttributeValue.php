<?php

namespace App\Entity;

use App\Repository\ItemAttributeValueRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: ItemAttributeValueRepository::class)]
#[ORM\UniqueConstraint(name: 'unique_item_attribute', columns: ['item_id', 'attribute_id'])]
class ItemAttributeValue
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\ManyToOne(inversedBy: 'itemAttributeValues')]
    #[ORM\JoinColumn(
        nullable: false,
        onDelete: 'CASCADE'
    )]
    private ?item $item = null;


    #[ORM\Column(type: Types::TEXT, nullable: true)]
    private ?string $value = null;

    #[ORM\ManyToOne(inversedBy: 'itemAttributeValues')]
    #[ORM\JoinColumn(name: 'attribute_id',nullable: false, onDelete: 'CASCADE')]
    private ?InventoryAttribute $inventoryAttribute = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getItem(): ?item
    {
        return $this->item;
    }

    public function setItem(?item $item): static
    {
        $this->item = $item;

        return $this;
    }


    public function getValue(): ?string
    {
        return $this->value;
    }

    public function setValue(?string $value): static
    {
        $this->value = $value;

        return $this;
    }

    public function getInventoryAttribute(): ?InventoryAttribute
    {
        return $this->inventoryAttribute;
    }

    public function setInventoryAttribute(?InventoryAttribute $inventoryAttribute): static
    {
        $this->inventoryAttribute = $inventoryAttribute;

        return $this;
    }
}
