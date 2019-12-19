<?php

namespace App\DataFixtures;

use App\Entity\Departement;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Persistence\ObjectManager;

class DepartementFixture extends Fixture
{
    public function load(ObjectManager $manager)
    {
        $departement1 = new Departement();
        $departement1->setNom('Direction');
        $departement1->setMailResponsable('rodriguetac@gmail.com');
        $manager->persist($departement1);

        $departement2 = new Departement();
        $departement2->setNom('rh');
        $departement2->setMailResponsable('rodriguetac59@gmail.com');
        $manager->persist($departement2);

        $manager->flush();
    }
}