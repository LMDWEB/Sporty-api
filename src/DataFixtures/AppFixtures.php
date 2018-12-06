<?php

namespace App\DataFixtures;

use App\Entity\Club;
use App\Entity\Player;
use App\Entity\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class AppFixtures extends Fixture
{
    public function __construct(UserPasswordEncoderInterface $encoder)
    {
        $this->encoder = $encoder;
    }

    public function load(ObjectManager $manager)
    {
        $club = new Club();
        $club->setName("Lyon");

        $manager->persist($club);

        $player = new Player();

        $player->setFullName("Memphis Depay")
                ->setPosition("attack")
                ->setHeight(1.80)
                ->setBirthdayDate(time())
                ->setAge(25)
                ->setFoot("right")
                ->setClub($club);

        $manager->persist($player);

        $user = new User("admin");
        $user->setPassword($this->encoder->encodePassword($user, "password"));
        $manager->persist($user);
        $manager->flush();
    }
}
