<?php

namespace App\Tests\Api;

use ApiPlatform\Core\Bridge\Symfony\Bundle\Test\ApiTestCase;
use App\DBAL\Types\MoonStateType;
use App\Entity\City;
use App\Entity\MoonPhase;
use Doctrine\ORM\EntityManagerInterface;
use Hautelook\AliceBundle\PhpUnit\RefreshDatabaseTrait;

class MoonPhaseTest extends ApiTestCase
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

        $client->request('GET', '/api/moon_phases');

        $this->assertResponseIsSuccessful();
        $this->assertResponseHeaderSame('content-type', 'application/ld+json; charset=utf-8');
    }

    public function testPostMoonPhase(): void
    {
        /* @var MoonPhase $city */
        $city = $this->entityManager->getRepository(City::class)->findOneBy(['name' => 'Lyon']);
        $client = static::createClient();
        $data = [
            'date' => '2021-01-01',
            'state' => MoonStateType::getRandomValue(),
        ];
        $client->request(
            'POST',
            '/api/moon_phases',
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

    public function testPostMoonPhaseAlreadyExists(): void
    {
        /* @var MoonPhase $moonPhaseAlready */
        $moonPhaseAlready = $this->entityManager->getRepository(MoonPhase::class)->findOneBy([]);

        $client = static::createClient();
        $data = [
            'date' => $moonPhaseAlready->getDate()->format('Y-m-d'),
            'state' => MoonStateType::getRandomValue(),
        ];
        $client->request(
            'POST',
            '/api/moon_phases',
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
            'hydra:description' => 'date: This moon phase already exists for this date.',
        ]);
        $this->assertResponseHeaderSame('content-type', 'application/ld+json; charset=utf-8');
    }
}
