<?php

namespace App\Entity;

use App\Enum\AttributeType;
use App\Repository\CustomFieldRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: CustomFieldRepository::class)]
class InventoryAttribute
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $name = null;

    #[ORM\Column(enumType: AttributeType::class)]
    private ?AttributeType $type = null;

    #[ORM\Column]
    private ?bool $required = null;

    #[ORM\ManyToOne(inversedBy: 'inventoryAttributes')]
    #[ORM\JoinColumn(
        nullable: false,
        onDelete: 'CASCADE'
    )]
    private ?Inventory $inventory = null;

    /**
     * @var Collection<int, ItemAttributeValue>
     */
    #[ORM\OneToMany(
        targetEntity: ItemAttributeValue::class,
        mappedBy: 'inventoryAttribute',
        cascade: ['persist', 'remove'],
        orphanRemoval: true
    )]
    private Collection $itemAttributeValues;

    public function __construct()
    {
        $this->itemAttributeValues = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name): static
    {
        $this->name = $name;

        return $this;
    }

    public function getType(): ?AttributeType
    {
        return $this->type;
    }

    public function setType(?AttributeType $type): static
    {
        $this->type = $type;
        return $this;
    }

    public function isRequired(): ?bool
    {
        return $this->required;
    }

    public function setRequired(bool $required): static
    {
        $this->required = $required;
        return $this;
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

    /**
     * @return Collection<int, ItemAttributeValue>
     */
    public function getItemAttributeValues(): Collection
    {
        return $this->itemAttributeValues;
    }

    public function addItemAttributeValue(ItemAttributeValue $itemAttributeValue): static
    {
        if (!$this->itemAttributeValues->contains($itemAttributeValue)) {
            $this->itemAttributeValues->add($itemAttributeValue);
            $itemAttributeValue->setInventoryAttribute($this);
        }

        return $this;
    }

    public function removeItemAttributeValue(ItemAttributeValue $itemAttributeValue): static
    {
        if ($this->itemAttributeValues->removeElement($itemAttributeValue)) {
            // set the owning side to null (unless already changed)
            if ($itemAttributeValue->getInventoryAttribute() === $this) {
                $itemAttributeValue->setInventoryAttribute(null);
            }
        }

        return $this;
    }


}
