<?php

namespace App\Entity;

use App\Repository\CategoryRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use ApiPlatform\Metadata\ApiResource;
use App\Controller\CategoryController;

use ApiPlatform\Metadata\GetCollection;
use ApiPlatform\Metadata\Get;
use ApiPlatform\Metadata\Post;
use ApiPlatform\Metadata\Put;
use ApiPlatform\Metadata\Patch;
use ApiPlatform\Metadata\Delete;

/** A category */
#[ApiResource(operations: [
    new GetCollection(
        name: 'app_category', 
        description: 'Get all categories',
        uriTemplate: '/categories', 
        controller: CategoryController::class
    ),
    new Get(
        name: 'app_category_by_id', 
        uriTemplate: '/categories/{id}', 
        controller: CategoryController::class
    ),
    new Post(
        name: 'app_category_add', 
        uriTemplate: '/categories/{id}', 
        controller: CategoryController::class
    ),
    new Put(
        name: 'app_category_put', 
        uriTemplate: '/categories/{id}', 
        controller: CategoryController::class
    ),
    new Patch(
        name: 'app_category_patch', 
        uriTemplate: '/categories/{id}', 
        controller: CategoryController::class
    ),
    new Delete(
        name: 'delete_category', 
        uriTemplate: '/categories/{id}', 
        controller: CategoryController::class
    )
])]
#[ORM\Entity(repositoryClass: CategoryRepository::class)]
class Category
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

    /**
     * @var Collection<int, Product> The collection of products associated to the category
     */
    #[ORM\OneToMany(targetEntity: Product::class, mappedBy: 'Category')]
    private Collection $products;

    public function __construct()
    {
        $this->products = new ArrayCollection();
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

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(?string $description): static
    {
        $this->description = $description;

        return $this;
    }

    /**
     * @return Collection<int, Product>
     */
    public function getProducts(): Collection
    {
        return $this->products;
    }

    public function addProduct(Product $product): static
    {
        if (!$this->products->contains($product)) {
            $this->products->add($product);
            $product->setCategory($this);
        }

        return $this;
    }

    public function removeProduct(Product $product): static
    {
        if ($this->products->removeElement($product)) {
            // set the owning side to null (unless already changed)
            if ($product->getCategory() === $this) {
                $product->setCategory(null);
            }
        }

        return $this;
    }
    
    public function getSerializeToArray(): ?array
    {
        return [
            'id' => $this->getId(),
            'name' => $this->getName(),
            'description' => $this->getDescription(),
            'products' => $this->getProductListSerializeToArray()
        ];
    }

    public function getSerializeToArraySimplified(): ?array
    {
        return [
            'id' => $this->getId(),
            'name' => $this->getName(),
            'description' => $this->getDescription()
        ];
    }

    public function compare(Category $category): ?bool
    {
        if ($category->getId() === $this->getId()) {
            return true;
        }
        return false;
    }

    public function getProductListSerializeToArray(): ?array
    {
        $productList = [];
        foreach ($this->getProducts() as $product) {
            $productList[] = $product->getSerializeToArray();
        }
        return $productList;
    }
}
