<?php

namespace App\Service;

use Doctrine\ORM\EntityManagerInterface;
use App\Entity\Category;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class CategoryService
{
    public $entityManager;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->em = $entityManager;
    }

    public function getAll(): Array
    {
        /** @var Category[] $categories */
        $categories = $this->em->getRepository(Category::class)->findAll();

        $response = [];
        foreach ($categories as $category) {
            $response[] = $category->getSerializeToArray();
        }

        return $response;
    }

    public function getByIdSerialize(String $id): Array
    {
        /** @var Category $category */
        $category = $this->getById($id);

        return $category->getSerializeToArray();
    }

    public function getById(String $id): Category
    {
        /** @var Category $category */
        $category = $this->em->getRepository(Category::class)->find($id);

        if (!isset($category)) {
            throw new NotFoundHttpException('Category not found');
        }

        return $category;
    }

    public function add(String $name, String $description = null): Category
    {
        /** @var Category $category */
        $category = $this->em->getRepository(Category::class)->findBy(['name' => $name]);

        if (!isset($category)) {
            throw new NotFoundHttpException('The category already exists');
        }

        /** @var Category $category */
        $category = new Category();
        $category->setName($name);
        $category->setDescription($description);

        $this->em->persist($category);
        $this->em->flush();

        return $category;
    }

    public function edit(String $id, String $name, String $description = null): Category
    {
        /** @var Category $category */
        $category = $this->getById($id);

        /** @var Category $categoryByName */
        $categoryByName = $this->em->getRepository(Category::class)->findOneBy(['name' => $name]);

        if (isset($categoryByName) && !$category->compare($categoryByName)) {
            throw new NotFoundHttpException('The category already exists');
        }

        /** @var Category $category */
        $category->setName($name);
        $category->setDescription($description);

        $this->em->persist($category);
        $this->em->flush();

        return $category;
    }

    public function delete(String $id): void
    {
        /** @var Category $category */
        $category = $this->getById($id);

        if (!empty($category->getProducts())) {
            throw new NotFoundHttpException('Category is assigned to products');
        }

        $this->em->remove($category);
        $this->em->flush();
    }
}
