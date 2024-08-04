<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use App\Service\ProductService;
use Symfony\Component\HttpFoundation\Request;

class ProductController extends AbstractController
{
    public function __construct(private ProductService $productService) {
        $this->productService = $productService;
    }

    #[Route('/products', name: 'app_product', methods: ['GET'])]
    public function getAll(): JsonResponse
    {
        return $this->json($this->productService->getAll());
    }

    #[Route('/products/{id}', name: 'app_product_by_id', methods: ['GET'])]
    public function getById(String $id): JsonResponse
    {
        if (empty($id)) {
            return new JsonResponse(['error' => 'The product ID cannot be empty'], 404);
        }

        try {
            return $this->json($this->productService->getByIdSerialize($id));
        } catch (\Exception $e) {
            return new JsonResponse(['error' => $e->getMessage()], $e->getStatusCode());
        }
    }

    #[Route('/products', name: 'app_product_add', methods: ['POST'])]
    public function add(Request $request): JsonResponse
    {
        /** @var Request $name $description $price $categoryId */
        $name = $request->get('name');
        $description = $request->get('description');
        $price = $request->get('price');
        $categoryId = $request->get('category');

        if (empty($categoryId)) {
            return new JsonResponse(['error' => 'The category ID cannot be empty'], 404);
        }

        if (empty($name)) {
            return new JsonResponse(['error' => 'The product name cannot be empty'], 404);
        }

        if (empty($price)) {
            return new JsonResponse(['error' => 'The product price cannot be empty'], 404);
        }

        if (!is_numeric($price)) {
            return new JsonResponse(['error' => 'Price must be a number'], 404);
        }

        try {
            $price = (float) $price;
        } catch (\Exception $e) {
            return new JsonResponse(['error' => 'Price has not a valid format'], 404);
        }
        
        try {
            /** @var Product $product */
            $product = $this->productService->add($name, $description, $price, $categoryId);
        } catch (\Exception $e) {
            return new JsonResponse(['error' => $e->getMessage()], $e->getStatusCode());
        }

        return $this->json($product->getSerializeToArray());
    }

    #[Route('/products/{id}', name: 'app_product_edit', methods: ['POST'])]
    public function edit(String $id, Request $request): JsonResponse
    {
        /** @var Request $name $description $price $categoryId */
        $name = $request->get('name');
        $description = $request->get('description');
        $price = $request->get('price');
        $categoryId = $request->get('category');

        if (empty($id)) {
            return new JsonResponse(['error' => 'The product ID cannot be empty'], 404);
        }

        if (empty($categoryId)) {
            return new JsonResponse(['error' => 'The category ID cannot be empty'], 404);
        }

        if (empty($name)) {
            return new JsonResponse(['error' => 'The product name cannot be empty'], 404);
        }

        if (empty($price)) {
            return new JsonResponse(['error' => 'The product price cannot be empty'], 404);
        }

        try {
            /** @var Product $product */
            $product = $this->productService->edit($id, $name, $description, $price, $categoryId);
        } catch (\Exception $e) {
            return new JsonResponse(['error' => $e->getMessage()], $e->getStatusCode());
        }

        return $this->json($product->getSerializeToArray());
    }

    #[Route('/products/{id}', name: "delete_product", methods: ['DELETE'])]
    public function remove(String $id): JsonResponse {
        if (empty($id)) {
            return new JsonResponse(['error' => 'The product ID cannot be empty'], 404);
        }

        try {
            $this->productService->delete($id);
        } catch (\Exception $e) {
            return new JsonResponse(['error' => $e->getMessage()], $e->getStatusCode());
        }
    
        return new JsonResponse(['message' => 'Deleted product', 'id' => $id]);
    }
}
