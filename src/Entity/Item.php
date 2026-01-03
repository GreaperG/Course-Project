<?php

namespace App\Entity;

use App\Repository\ItemRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: ItemRepository::class)]
class Item
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $customId = null;

    #[ORM\ManyToOne(inversedBy: 'items')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Inventory $inventory = null;

    #[ORM\ManyToOne]
    #[ORM\JoinColumn(nullable: false)]
    private ?User $createdBy = null;

    #[ORM\Version]
    #[ORM\Column(type: 'integer')]
    private int $version = 1;

    #[ORM\Column(type: 'datetime')]
    private ?\DateTime $createdAt = null;

    #[ORM\Column(type: 'datetime')]
    private ?\DateTime $updatedAt = null;

    /**
     * @var Collection<int, ItemAttributeValue>
     */
    #[ORM\OneToMany(
        targetEntity: ItemAttributeValue::class,
        mappedBy: 'item',
        cascade: ['persist'],
        orphanRemoval: true
    )]
    private Collection $itemAttributeValues;

    public function __construct()
    {
        $this->createdAt = new \DateTime();
        $this->updatedAt = new \DateTime();
        $this->itemAttributeValues = new ArrayCollection();
    }


    public function getId(): ?int
    {
        return $this->id;
    }

    public function getCustomId(): ?string
    {
        return $this->customId;
    }

    public function setCustomId(string $customId): static
    {
        $this->customId = $customId;

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

    public function getCreatedBy(): ?User
    {
        return $this->createdBy;
    }

    public function setCreatedBy(?User $createdBy): static
    {
        $this->createdBy = $createdBy;

        return $this;
    }

    public function getVersion(): int
    {
        return $this->version;
    }

    public function setVersion(int $version): static
    {
        $this->version = $version;
        return $this;
    }

    public function getCreatedAt(): ?\DateTimeInterface
    {
        return $this->createdAt;
    }

    public function setCreatedAt(\DateTimeInterface $createdAt): static
    {
        $this->createdAt = $createdAt;
        return $this;
    }

    public function getUpdatedAt(): ?\DateTimeInterface
    {
        return $this->updatedAt;
    }

    public function setUpdatedAt(\DateTimeInterface $updatedAt): static
    {
        $this->updatedAt = $updatedAt;
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
            $itemAttributeValue->setItem($this);
        }

        return $this;
    }

    public function removeItemAttributeValue(ItemAttributeValue $itemAttributeValue): static
    {
        if ($this->itemAttributeValues->removeElement($itemAttributeValue)) {
            // set the owning side to null (unless already changed)
            if ($itemAttributeValue->getItem() === $this) {
                $itemAttributeValue->setItem(null);
            }
        }

        return $this;
    }
}
