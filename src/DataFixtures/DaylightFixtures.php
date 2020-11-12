<?php


namespace App\DataFixtures;


use App\Entity\Daylight;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class DaylightFixtures extends Fixture
{

    public function load(ObjectManager $manager)
    {
        for ($count = 1; $count < 20; $count++) {

            $sunrise = new \DateTime("2020-10-$count 07:20:00");
            $sunrise->add(new \DateInterval("PT{$count}M"));

            $sunset = new \DateTime('2020-10-'.$count.' 17:23:00');
            $sunset->sub(new \DateInterval("PT{$count}M"));

            $daylight = new Daylight();
            $daylight
                ->setDate(new \DateTime('2020-10-'.$count))
                ->setSunrise($sunrise)
                ->setSunset($sunset)
                ->setCity('Lyon');
            $manager->persist($daylight);
        }

        $manager->flush();
    }
}