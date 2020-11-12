<?php


namespace App\DataFixtures;


use App\DBAL\Types\MoonStateType;
use App\Entity\MoonPhase;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class MoonPhaseFixtures extends Fixture
{

    public function load(ObjectManager $manager)
    {
        for ($count = 1; $count < 20; $count++) {
            $moonPhase = new MoonPhase();
            $moonPhase->setDate(new \DateTime("2020-10-$count"));
            $moonPhase->setState($this->getRandomMoonSate());

            $manager->persist($moonPhase);
        }

        $manager->flush();
    }

    private function getRandomMoonSate(): string
    {
        return MoonStateType::getRandomValue();
    }
}