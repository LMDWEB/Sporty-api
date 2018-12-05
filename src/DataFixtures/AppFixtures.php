<?php

namespace App\DataFixtures;

use App\Entity\Player;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Persistence\ObjectManager;

class AppFixtures extends Fixture
{
    public function load(ObjectManager $manager)
    {
        $player = new Player();
        $player->setFirstName("Memphis")
                ->setLastName("Depay");

        // $product = new Product();
        $manager->persist($player);

        $manager->flush();
    }
}
