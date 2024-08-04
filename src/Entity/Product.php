<?php

namespace App\Entity;

use App\Repository\ProductRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use ApiPlatform\Metadata\ApiResource;

/** A product */
#[ORM\Entity(repositoryClass: ProductRepository::class)]
class Product
{
    /** The ID */
    #[ORM\Id]
    #[ORM\GeneratedValue(strategy: "SEQUENCE")]
    #[ORM\Column]
    private ?int $id = null;

    /** The name */
    #[ORM\Column(length: 255)]
    private ?string $name = null;

    /** The description */
    #[ORM\Column(length: 255, nullable: true)]
    private ?string $description = null;

    /** The price with 2 decimals */
    #[ORM\Column(type: Types::DECIMAL, precision: 10, scale: 2)]
    private ?string $price = null;

    /** The product category */
    #[ORM\ManyToOne(inversedBy: 'products')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Category $Category = null;

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

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(?string $description): static
    {
        $this->description = $description;

        return $this;
    }

    public function getPrice(): ?string
    {
        return $this->price;
    }

    public function setPrice(string $price): static
    {
        $this->price = $price;

        return $this;
    }

    public function getCategory(): ?Category
    {
        return $this->Category;
    }

    public function setCategory(?Category $Category): static
    {
        $this->Category = $Category;

        return $this;
    }

    public function compare(Product $product): ?bool
    {
        if ($product->getId() === $this->getId()) {
            return true;
        }
        return false;
    }

    public function getSerializeToArray(): ?array
    {
        /** @var Category $category */
        $category = $this->getCategory();

        return [
            'id' => $this->getId(),
            'name' => $this->getName(),
            'description' => $this->getDescription(),
            'price' => $this->getPrice(),
            'category' => $category->getSerializeToArraySimplified()
        ];
    }
}
