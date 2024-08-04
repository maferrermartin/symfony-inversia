<?php

namespace App\Service;

use Doctrine\ORM\EntityManagerInterface;
use App\Entity\Product;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use App\Service\CategoryService;

class ProductService
{
    public function __construct(private EntityManagerInterface $entityManager, private CategoryService $categoryService) {
        $this->em = $entityManager;
        $this->categoryService = $categoryService;
    }

    public function getAll(): Array
    {
        /** @var Product[] $products */
        $products = $this->em->getRepository(Product::class)->findAll();

        $response = [];
        foreach ($products as $product) {
            $response[] = $product->getSerializeToArray();
        }

        return $response;
    }

    public function getByIdSerialize(String $id): Array
    {
        /** @var Product $product */
        $product = $this->getById($id);

        return $product->getSerializeToArray();
    }

    public function getById(String $id): Product
    {
        /** @var Product $product */
        $product = $this->em->getRepository(Product::class)->find($id);

        if (!isset($product)) {
            throw new NotFoundHttpException('Product not found');
        }

        return $product;
    }

    public function add(String $name, String $description = null, Float $price, String $categoryId): Product
    {
        /** @var Category $category */
        $category = $this->categoryService->getById($categoryId);

        /** @var Product $productByName */
        $productByName = $this->em->getRepository(Product::class)->findOneBy(['name' => $name]);

        if (isset($productByName)) {
            throw new NotFoundHttpException('The product already exists');
        }

        if ($price < 0) {
            throw new NotFoundHttpException('Price must be higher or equal than 0');
        }
 
        /** @var Product $product */
        $product = new Product();
        $product->setName($name);
        $product->setDescription($description);
        $product->setPrice($price);
        $product->setCategory($category);

        $this->em->persist($product);
        $this->em->flush();

        return $product;
    }

    public function edit(String $id, String $name, String $description = null, float $price, String $categoryId): Product
    {
        /** @var Product $product */
        $product = $this->getById($id);

        /** @var Category $category */
        $category = $this->categoryService->getById($categoryId);

        /** @var Product $productByName */
        $productByName = $this->em->getRepository(Product::class)->findOneBy(['name' => $name]);

        if (isset($productByName) && !$product->compare($productByName)) {
            throw new NotFoundHttpException('The product already exists');
        }

        if ($price < 0) {
            throw new NotFoundHttpException('Price must be higher or equal than 0');
        }

        /** @var Product $product */
        $product->setName($name);
        $product->setDescription($description);
        $product->setPrice($price);
        $product->setCategory($category);

        $this->em->persist($product);
        $this->em->flush();

        return $product;
    }

    public function delete(String $id): void
    {
        /** @var Product $product */
        $product = $this->getById($id);

        $this->em->remove($product);
        $this->em->flush();
    }
}
