<?php

namespace App\Tests\Api;

use ApiPlatform\Core\Bridge\Symfony\Bundle\Test\ApiTestCase;
use App\Entity\River;
use Doctrine\ORM\EntityManagerInterface;
use Hautelook\AliceBundle\PhpUnit\RefreshDatabaseTrait;

class RiverTest extends ApiTestCase
{
    use RefreshDatabaseTrait;

    private EntityManagerInterface $entityManager;

    protected function setUp(): void
    {
        $kernel = self::bootKernel();

        $this->entityManager = $kernel->getContainer()
            ->get('doctrine')
            ->getManager();
    }

    public function testGetCollection(): void
    {
        $client = static::createClient();

        $client->request('GET', '/api/rivers');

        $this->assertResponseIsSuccessful();
        $this->assertResponseHeaderSame('content-type', 'application/ld+json; charset=utf-8');
    }

    public function testPostRiver(): void
    {
        $client = static::createClient();
        $data = [
            'name' => 'Ma petite rivière',
        ];
        $client->request(
            'POST',
            '/api/rivers',
            [
                'headers' => [
                    'Content-Type' => 'application/ld+json',
                ],
                'body' => json_encode($data),
            ]
        );

        $this->assertResponseIsSuccessful();
        $this->assertResponseHeaderSame('content-type', 'application/ld+json; charset=utf-8');
    }

    public function testPostRiverAlreadyExists(): void
    {
        /* @var River $river */
        $river = $this->entityManager->getRepository(River::class)->findOneBy(['name' => 'La Saône']);

        $client = static::createClient();
        $data = [
            'name' => $river->getName(),
        ];
        $client->request(
            'POST',
            '/api/rivers',
            [
                'headers' => [
                    'Content-Type' => 'application/ld+json',
                ],
                'body' => json_encode($data),
            ]
        );

        $this->assertResponseStatusCodeSame(400);
        $this->assertJsonContains([
            '@context' => '/api/contexts/ConstraintViolationList',
            '@type' => 'ConstraintViolationList',
            'hydra:title' => 'An error occurred',
            'hydra:description' => 'name: This river already exists.',
        ]);
        $this->assertResponseHeaderSame('content-type', 'application/ld+json; charset=utf-8');
    }
}
