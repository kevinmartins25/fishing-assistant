<?php

namespace App\Tests\Api;

use ApiPlatform\Core\Bridge\Symfony\Bundle\Test\ApiTestCase;
use App\Entity\City;
use Doctrine\ORM\EntityManagerInterface;
use Hautelook\AliceBundle\PhpUnit\RefreshDatabaseTrait;

class WatherTestextends extends ApiTestCase
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

        $client->request('GET', '/api/weather');

        $this->assertResponseIsSuccessful();
        $this->assertResponseHeaderSame('content-type', 'application/ld+json; charset=utf-8');
    }

    public function testPostWather(): void
    {
        $client = static::createClient();

        /* @var City $city */
        $city = $this->entityManager->getRepository(City::class)->findOneBy([]);

        $data = [
            'city' => '/api/cities/'.$city->getId(),
            'time' => date('Y-m-d H:m:s'),
            'windy' => 10,
            'temperature' => 15,
            'state' => 'Temps clair',
        ];

        $client->request(
            'POST',
            '/api/weather',
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

    public function testPostWatherWithoutCity(): void
    {
        $client = static::createClient();

        $data = [
            'time' => date('Y-m-d H:m:s'),
            'windy' => 10,
            'temperature' => 15,
            'state' => 'Temps clair',
        ];

        $client->request(
            'POST',
            '/api/weather',
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
            'hydra:description' => 'city: This value should not be blank.',
        ]);
        $this->assertResponseHeaderSame('content-type', 'application/ld+json; charset=utf-8');
    }

    public function testPostWatherWithoutTime(): void
    {
        $client = static::createClient();

        /* @var City $city */
        $city = $this->entityManager->getRepository(City::class)->findOneBy([]);

        $data = [
            'city' => '/api/cities/'.$city->getId(),
            'windy' => 10,
            'temperature' => 15,
            'state' => 'Temps clair',
        ];

        $client->request(
            'POST',
            '/api/weather',
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
            'hydra:description' => 'time: This value should not be blank.',
        ]);
        $this->assertResponseHeaderSame('content-type', 'application/ld+json; charset=utf-8');
    }

    public function testPostWatherWithoutWindy(): void
    {
        $client = static::createClient();

        /* @var City $city */
        $city = $this->entityManager->getRepository(City::class)->findOneBy([]);

        $data = [
            'city' => '/api/cities/'.$city->getId(),
            'time' => date('Y-m-d H:m:s'),
            'temperature' => 15,
            'state' => 'Temps clair',
        ];

        $client->request(
            'POST',
            '/api/weather',
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
            'hydra:description' => 'windy: This value should not be blank.',
        ]);
        $this->assertResponseHeaderSame('content-type', 'application/ld+json; charset=utf-8');
    }

    public function testPostWatherWithoutTemperature(): void
    {
        $client = static::createClient();

        /* @var City $city */
        $city = $this->entityManager->getRepository(City::class)->findOneBy([]);

        $data = [
            'city' => '/api/cities/'.$city->getId(),
            'time' => date('Y-m-d H:m:s'),
            'windy' => 10,
            'state' => 'Temps clair',
        ];

        $client->request(
            'POST',
            '/api/weather',
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
            'hydra:description' => 'temperature: This value should not be blank.',
        ]);
        $this->assertResponseHeaderSame('content-type', 'application/ld+json; charset=utf-8');
    }

    public function testPostWatherWithoutState(): void
    {
        $client = static::createClient();

        /* @var City $city */
        $city = $this->entityManager->getRepository(City::class)->findOneBy([]);

        $data = [
            'city' => '/api/cities/'.$city->getId(),
            'time' => date('Y-m-d H:m:s'),
            'temperature' => 15,
            'windy' => 10,
        ];

        $client->request(
            'POST',
            '/api/weather',
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
            'hydra:description' => 'state: This value should not be blank.',
        ]);
        $this->assertResponseHeaderSame('content-type', 'application/ld+json; charset=utf-8');
    }

    public function testPostWatherWithNegativeWIndy(): void
    {
        $client = static::createClient();

        /* @var City $city */
        $city = $this->entityManager->getRepository(City::class)->findOneBy([]);

        $data = [
            'city' => '/api/cities/'.$city->getId(),
            'time' => date('Y-m-d H:m:s'),
            'windy' => -10,
            'temperature' => 15,
            'state' => 'Temps clair',
        ];

        $client->request(
            'POST',
            '/api/weather',
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
            'hydra:description' => 'windy: This value should be greater than or equal to "0".',
        ]);
        $this->assertResponseHeaderSame('content-type', 'application/ld+json; charset=utf-8');
    }
}
