<?php

namespace App\Tests\Api;

use ApiPlatform\Core\Bridge\Symfony\Bundle\Test\ApiTestCase;
use App\Entity\River;
use App\Entity\Station;
use Doctrine\ORM\EntityManagerInterface;
use Hautelook\AliceBundle\PhpUnit\RefreshDatabaseTrait;

class WaterHeightTestextends extends ApiTestCase
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

        $client->request('GET', '/api/water_heights');

        $this->assertResponseIsSuccessful();
        $this->assertResponseHeaderSame('content-type', 'application/ld+json; charset=utf-8');
    }

    public function testPostWaterHeight(): void
    {
        $client = static::createClient();

        /* @var River $river */
        $river = $this->entityManager->getRepository(River::class)->findOneBy([]);

        /* @var Station $station */
        $station = $this->entityManager->getRepository(Station::class)->findOneBy([]);

        $data = [
            'river' => '/api/rivers/'.$river->getId(),
            'station' => '/api/stations/'.$station->getId(),
            'value' => 1.35,
            'dateTime' => date('Y-m-d H:m:s'),
        ];

        $client->request(
            'POST',
            '/api/water_heights',
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

    public function testPostWaterHeightWithoutRiver(): void
    {
        $client = static::createClient();

        /* @var Station $station */
        $station = $this->entityManager->getRepository(Station::class)->findOneBy([]);

        $data = [
            'station' => '/api/stations/'.$station->getId(),
            'value' => 1.35,
            'dateTime' => date('Y-m-d H:m:s'),
        ];

        $client->request(
            'POST',
            '/api/water_heights',
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
            'hydra:description' => 'river: This value should not be blank.',
        ]);
        $this->assertResponseHeaderSame('content-type', 'application/ld+json; charset=utf-8');
    }

    public function testPostWaterHeightWithoutStation(): void
    {
        $client = static::createClient();

        /* @var River $river */
        $river = $this->entityManager->getRepository(River::class)->findOneBy([]);

        $data = [
            'river' => '/api/rivers/'.$river->getId(),
            'value' => 1.35,
            'dateTime' => date('Y-m-d H:m:s'),
        ];

        $client->request(
            'POST',
            '/api/water_heights',
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
            'hydra:description' => 'station: This value should not be blank.',
        ]);
        $this->assertResponseHeaderSame('content-type', 'application/ld+json; charset=utf-8');
    }

    public function testPostWaterHeightWithoutValue(): void
    {
        $client = static::createClient();

        /* @var River $river */
        $river = $this->entityManager->getRepository(River::class)->findOneBy([]);

        /* @var Station $station */
        $station = $this->entityManager->getRepository(Station::class)->findOneBy([]);

        $data = [
            'river' => '/api/rivers/'.$river->getId(),
            'station' => '/api/stations/'.$station->getId(),
            'dateTime' => date('Y-m-d H:m:s'),
        ];

        $client->request(
            'POST',
            '/api/water_heights',
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
            'hydra:description' => 'value: This value should not be blank.',
        ]);
        $this->assertResponseHeaderSame('content-type', 'application/ld+json; charset=utf-8');
    }

    public function testPostWaterHeightNegativeValue(): void
    {
        $client = static::createClient();

        /* @var River $river */
        $river = $this->entityManager->getRepository(River::class)->findOneBy([]);

        /* @var Station $station */
        $station = $this->entityManager->getRepository(Station::class)->findOneBy([]);

        $data = [
            'river' => '/api/rivers/'.$river->getId(),
            'station' => '/api/stations/'.$station->getId(),
            'value' => -1,
            'dateTime' => date('Y-m-d H:m:s'),
        ];

        $client->request(
            'POST',
            '/api/water_heights',
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
            'hydra:description' => 'value: This value should be greater than or equal to "0".',
        ]);
        $this->assertResponseHeaderSame('content-type', 'application/ld+json; charset=utf-8');
    }

    public function testPostWaterHeightWithDateInTheFuture(): void
    {
        $client = static::createClient();

        /* @var River $river */
        $river = $this->entityManager->getRepository(River::class)->findOneBy([]);

        /* @var Station $station */
        $station = $this->entityManager->getRepository(Station::class)->findOneBy([]);

        $data = [
            'river' => '/api/rivers/'.$river->getId(),
            'station' => '/api/stations/'.$station->getId(),
            'value' => -1,
            'dateTime' => date('Y-m-d H:m:s', strtotime('now + 2 days')),
        ];

        $client->request(
            'POST',
            '/api/water_heights',
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
        ]);
        $this->assertResponseHeaderSame('content-type', 'application/ld+json; charset=utf-8');
    }

    public function testPostWaterHeightWithoutDateTime(): void
    {
        $client = static::createClient();

        /* @var River $river */
        $river = $this->entityManager->getRepository(River::class)->findOneBy([]);

        /* @var Station $station */
        $station = $this->entityManager->getRepository(Station::class)->findOneBy([]);

        $data = [
            'river' => '/api/rivers/'.$river->getId(),
            'station' => '/api/stations/'.$station->getId(),
            'value' => 1,
        ];

        $client->request(
            'POST',
            '/api/water_heights',
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
            'hydra:description' => 'dateTime: This value should not be blank.',
        ]);
        $this->assertResponseHeaderSame('content-type', 'application/ld+json; charset=utf-8');
    }
}
