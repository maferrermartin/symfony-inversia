<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use App\Service\CategoryService;
use Symfony\Component\HttpFoundation\Request;

class CategoryController extends AbstractController
{
    public function __construct(private CategoryService $categoryService) {
        $this->categoryService = $categoryService;
    }

    #[Route('/categories', name: 'app_category', methods: ['GET'])]
    public function getAll(): JsonResponse
    {
        return $this->json($this->categoryService->getAll());
    }

    #[Route('/categories/{id}', name: 'app_category_by_id', methods: ['GET'])]
    public function getById(String $id): JsonResponse
    {
        if (empty($id)) {
            return new JsonResponse(['error' => 'The category ID cannot be empty'], 404);
        }

        try {
            return $this->json($this->categoryService->getByIdSerialize($id));
        } catch (\Exception $e) {
            return new JsonResponse(['error' => $e->getMessage()], $e->getStatusCode());
        }
    }

    #[Route('/categories', name: 'app_category_add', methods: ['POST'])]
    public function add(Request $request): JsonResponse
    {
        /** @var Request $name $description */
        $name = $request->get('name');
        $description = $request->get('description');

        return new JsonResponse(['error' => $name], 404);

        if (empty($name)) {
            return new JsonResponse(['error' => 'The category name cannot be empty'], 404);
        }

        try {
            /** @var Category $category */
            $category = $this->categoryService->add($name, $description);
        } catch (\Exception $e) {
            return new JsonResponse(['error' => $e->getMessage()], $e->getStatusCode());
        }

        return $this->json($category->getSerializeToArray());
    }

    #[Route('/categories/{id}', name: 'app_category_edit', methods: ['POST'])]
    public function edit(String $id, Request $request): JsonResponse
    {
        /** @var Request $name $description */
        $name = $request->get('name');
        $description = $request->get('description');

        if (empty($id)) {
            return new JsonResponse(['error' => 'The category ID cannot be empty'], 404);
        }

        if (empty($name)) {
            return new JsonResponse(['error' => 'The category name cannot be empty'], 404);
        }

        try {
            /** @var Category $category */
            $category = $this->categoryService->edit($id, $name, $description);
        } catch (\Exception $e) {
            return new JsonResponse(['error' => $e->getMessage()], $e->getStatusCode());
        }

        return $this->json($category->getSerializeToArray());
    }

    #[Route('/categories/{id}', name: "delete_category", methods: ['DELETE'])]
    public function remove(String $id): JsonResponse {
        if (empty($id)) {
            return new JsonResponse(['error' => 'The category ID cannot be empty'], 404);
        }

        try {
            $this->categoryService->delete($id);
        } catch (\Exception $e) {
            return new JsonResponse(['error' => $e->getMessage()], $e->getStatusCode());
        }
        
        return new JsonResponse(['message' => 'Deleted category', 'id' => $id]);
    }
}
