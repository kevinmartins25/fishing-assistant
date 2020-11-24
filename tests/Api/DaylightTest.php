<?php

namespace App\Tests\Api;

use ApiPlatform\Core\Bridge\Symfony\Bundle\Test\ApiTestCase;
use App\Entity\City;
use App\Entity\Daylight;
use Doctrine\ORM\EntityManagerInterface;
use Hautelook\AliceBundle\PhpUnit\RefreshDatabaseTrait;

class DaylightTest extends ApiTestCase
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

        $client->request('GET', '/api/daylights');

        $this->assertResponseIsSuccessful();
        $this->assertResponseHeaderSame('content-type', 'application/ld+json; charset=utf-8');
    }

    public function testPostDaylight(): void
    {
        /* @var City $city */
        $city = $this->entityManager->getRepository(City::class)->findOneBy(['name' => 'Lyon']);
        $client = static::createClient();
        $data = [
            'date' => '2021-01-01',
            'sunrise' => '2020-01-01 07:09:10',
            'sunset' => '2020-01-01 18:03:10',
            'city' => '/api/cities/'.$city->getId(),
        ];
        $client->request(
            'POST',
            '/api/daylights',
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

    public function testPostDaylightAlreadyExists(): void
    {
        /* @var City $city */
        $city = $this->entityManager->getRepository(City::class)->findOneBy(['name' => 'Lyon']);

        /* @var Daylight $daylightAlreadyExists */
        $daylightAlreadyExists = $this->entityManager->getRepository(Daylight::class)->findOneBy(['city' => $city->getId()]);

        $client = static::createClient();
        $data = [
            'date' => $daylightAlreadyExists->getDate()->format('Y-m-d H:i:s'),
            'sunrise' => '2020-01-01 07:09:10',
            'sunset' => '2020-01-01 18:03:10',
            'city' => '/api/cities/'.$city->getId(),
        ];
        $client->request(
            'POST',
            '/api/daylights',
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
            'hydra:description' => 'date: This dayligth already exists for this date and city.',
        ]);
        $this->assertResponseHeaderSame('content-type', 'application/ld+json; charset=utf-8');
    }
}
