<?php

namespace EventBundle\DataFixtures\ORM;

use Doctrine\Bundle\FixturesBundle\ORMFixtureInterface;
use Doctrine\Common\DataFixtures\FixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;
use EventBundle\Entity\Evenement;

class LoadEvenementData extends Fixture
{
    public function load(ObjectManager $manager)
    {
        $schedule = new Evenement('t1', new \DateTime());
        $schedule->setNomEvenement('Yoga class');
        $today = new \DateTime();
        $schedule->setDescription('d1');
        $schedule->setDateDebut($today);
        $schedule->setDateFin($today);

        $manager->persist($schedule);
        $schedule = new Evenement('t1', new \DateTime());
        $schedule->setNomEvenement('German class');
        $tomorrow = new \DateTime('tomorrow');
        $schedule->setDateDebut($tomorrow);
        $schedule->setDateFin($tomorrow);
        $schedule->setDescription('d2');
        $manager->persist($schedule);
        $manager->flush();
    }
}