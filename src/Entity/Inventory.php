<?php

namespace App\Entity;

use App\Repository\InventoryRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: InventoryRepository::class)]
class Inventory
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 100)]
    private ?string $title = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $description = null;

    #[ORM\Column(length: 100)]
    private ?string $category = null;

    #[ORM\Column]
    private ?bool $isPublic = null;

    #[ORM\Version()]
    #[ORM\Column(type: 'integer')]
    private int $version = 1;

    #[ORM\Column]
    private ?\DateTime $createdAt = null;

    #[ORM\Column]
    private ?\DateTime $updatedAt = null;

    /**
     * @var Collection<int, Item>
     */
    #[ORM\OneToMany(targetEntity: Item::class, mappedBy: 'inventory')]
    private Collection $items;

    #[ORM\ManyToOne(inversedBy: 'inventories')]
    #[ORM\JoinColumn(nullable: false)]
    private ?User $owner = null;

    #[ORM\OneToMany(
        targetEntity: InventoryAttribute::class,
        mappedBy: 'inventory',
        cascade: ['persist', 'remove'],
        orphanRemoval: true
    )]
    private Collection $inventoryAttributes;

    /**
     * @var Collection<int, InventoryAccess>
     */
    #[ORM\OneToMany(targetEntity: InventoryAccess::class, mappedBy: 'inventory', cascade: ['persist', 'remove'],)]
    private Collection $inventoryAccesses;

    public function __construct()
    {
        $this->inventoryAttributes = new ArrayCollection();
        $this->isPublic = false;
        $this->createdAt = new \DateTime();
        $this->updatedAt = new \DateTime();
        $this->items = new ArrayCollection();
        $this->inventoryAccesses = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getTitle(): ?string
    {
        return $this->title;
    }

    public function setTitle(string $title): static
    {
        $this->title = $title;

        return $this;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(?string $description): static
    {
        $this->description = $description;

        return $this;
    }

    public function getCategory(): ?string
    {
        return $this->category;
    }

    public function setCategory(string $category): static
    {
        $this->category = $category;

        return $this;
    }

    public function isPublic(): ?bool
    {
        return $this->isPublic;
    }

    public function setIsPublic(bool $isPublic): static
    {
        $this->isPublic = $isPublic;

        return $this;
    }

    public function getVersion(): ?string
    {
        return $this->version;
    }

    public function setVersion(string $version): static
    {
        $this->version = $version;

        return $this;
    }

    public function getCreatedAt(): ?\DateTime
    {
        return $this->createdAt;
    }

    public function setCreatedAt(\DateTime $createdAt): static
    {
        $this->createdAt = $createdAt;

        return $this;
    }

    public function getUpdatedAt(): ?\DateTime
    {
        return $this->updatedAt;
    }

    public function setUpdatedAt(\DateTime $updatedAt): static
    {
        $this->updatedAt = $updatedAt;

        return $this;
    }

    /**
     * @return Collection<int, Item>
     */
    public function getItems(): Collection
    {
        return $this->items;
    }

    public function addItem(Item $item): static
    {
        if (!$this->items->contains($item)) {
            $this->items->add($item);
            $item->setInventory($this);
        }

        return $this;
    }

    public function removeItem(Item $item): static
    {
        if ($this->items->removeElement($item)) {
            // set the owning side to null (unless already changed)
            if ($item->getInventory() === $this) {
                $item->setInventory(null);
            }
        }

        return $this;
    }

    public function getOwner(): ?User
    {
        return $this->owner;
    }

    public function setOwner(?User $owner): static
    {
        $this->owner = $owner;

        return $this;
    }


    public function getInventoryAttributes(): Collection
    {
        return $this->inventoryAttributes;
    }

    public function addInventoryAttribute(InventoryAttribute $inventoryAttribute): static
    {
        if (!$this->inventoryAttributes->contains($inventoryAttribute)) {
            $this->inventoryAttributes->add($inventoryAttribute);
            $inventoryAttribute->setInventory($this);
        }
        return $this;
    }

    public function removeInventoryAttribute(InventoryAttribute $inventoryAttribute): static
    {
        if ($this->inventoryAttributes->removeElement($inventoryAttribute)) {
            // set the owning side to null (unless already changed)
            if ($inventoryAttribute->getInventory() === $this) {
                $inventoryAttribute->setInventory(null);
            }
        }
        return $this;
    }

    /**
     * @return Collection<int, InventoryAccess>
     */
    public function getInventoryAccesses(): Collection
    {
        return $this->inventoryAccesses;
    }

    public function addInventoryAccesses(InventoryAccess $inventoryAccesses): static
    {
        if (!$this->inventoryAccesses->contains($inventoryAccesses)) {
            $this->inventoryAccesses->add($inventoryAccesses);
            $inventoryAccesses->setInventory($this);
        }

        return $this;
    }

    public function removeInventoryAccesses(InventoryAccess $inventoryAccesses): static
    {
        if ($this->inventoryAccesses->removeElement($inventoryAccesses)) {
            // set the owning side to null (unless already changed)
            if ($inventoryAccesses->getInventory() === $this) {
                $inventoryAccesses->setInventory(null);
            }
        }

        return $this;
    }
}
