<?php

namespace App\Tests;

use ApiPlatform\Symfony\Bundle\Test\ApiTestCase;
use App\Entity\Category;
use App\Factory\CategoryFactory;
use Zenstruck\Foundry\Test\Factories;
use Zenstruck\Foundry\Test\ResetDatabase;
use Symfony\Component\HttpClient\HttpClient;

class CategoryTest extends ApiTestCase
{
    // This trait provided by Foundry will take care of refreshing the database content to a known state before each test
    use ResetDatabase, Factories;

    public function testGetCollection(): void
    {
        CategoryFactory::createMany(100);
    
        $response = static::createClient()->request('GET', '/categories');

        $this->assertResponseIsSuccessful();
       
        // Asserts that the returned content type is JSON
        $this->assertResponseHeaderSame('content-type', 'application/json');
    }

    /*
    public function testCreateCategory(): void
    {
        $client = static::createClient();
        $response = $client->request('POST', '/categories', ['json' => [
            'name' => 'TEST_NAME'
        ]]);

        $this->assertResponseIsSuccessful();
        $this->assertResponseHeaderSame('content-type', 'application/json');

        $iri = $this->findIriBy(Category::class, ['name' => 'TEST_NAME']);
        $this->assertJsonContains([
            'id' => $iri,
            'name' => 'TEST_NAME',
            'description' => 'TEST_DESCRIPTION',
            'products' => []
        ]);
        $this->assertMatchesRegularExpression('~^/categories/\d+$~', $response->toArray()['@id']);
    }

    public function testCreateInvalidBook(): void
    {
        static::createClient()->request('POST', '/categories', ['json' => [
            'name' => null,
            'description' => 'TEST_DESCRIPTION'
        ]]);

        $this->assertResponseStatusCodeSame(404);
        $this->assertResponseHeaderSame('content-type', 'application/json');

        $this->assertJsonContains([
            '@context' => '/contexts/ConstraintViolationList',
            '@type' => 'ConstraintViolationList',
            'hydra:error' => 'The category name cannot be empty'
        ]);

        static::createClient()->request('POST', '/categories', ['json' => [
            'name' => 'TEST_NAME',
            'description' => 'TEST_DESCRIPTION'
        ]]);

        $this->assertResponseStatusCodeSame(404);
        $this->assertResponseHeaderSame('content-type', 'application/json');

        $this->assertJsonContains([
            '@context' => '/contexts/ConstraintViolationList',
            '@type' => 'ConstraintViolationList',
            'hydra:error' => 'The category already exists'
        ]);
    }

    public function testUpdateCategory(): void
    {
        CategoryFactory::createOne(['name' => 'TEST_UPDATE']);
    
        $client = static::createClient();
        $iri = $this->findIriBy(Category::class, ['name' => 'TEST_UPDATE']);

        // Use the PATCH method here to do a partial update
        $client->request('PATCH', $iri, [
            'json' => [
                'name' => 'TEST_UPDATE',
                'description' => 'DESCRIPTION_UPDATE'
            ],
            'headers' => ['Content-Type' => 'application/merge-patch+json']
        ]);

        $this->assertResponseIsSuccessful();
        $this->assertJsonContains([
            'id' => $iri,
            'name' => 'TEST_UPDATE',
            'description' => 'DESCRIPTION_UPDATE',
            'products' => []
        ]);
    }

    public function testDeleteCategory(): void
    {
        CategoryFactory::createOne(['name' => 'TEST_TO_DELETE']);

        $client = static::createClient();
        $iri = $this->findIriBy(Category::class, ['name' => 'TEST_TO_DELETE']);

        $client->request('DELETE', $iri);

        $this->assertResponseIsSuccessful();
        $this->assertNull(
            static::getContainer()->get('doctrine')->getRepository(Category::class)->findOneBy(['name' => 'TEST_TO_DELETE'])
        );
    }
    */
}
