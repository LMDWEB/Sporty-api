<?php

namespace App\DataFixtures;

use App\Entity\Role;
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

        $adminRole = new Role();
        $adminRole->setTitle("ROLE_ADMIN");
        $manager->persist($adminRole);

        $freeRole = new Role();
        $freeRole->setTitle("ROLE_API");
        $manager->persist($freeRole);

        $user = new User("admin");
        $user->setPassword($this->encoder->encodePassword($user, "password"));
        $user->addUserRole($adminRole);
        $user->setNbRequests(0);
        $user->setNbRequestMax(999);
        $manager->persist($user);

        $user2 = new User("random");
        $user2->setPassword($this->encoder->encodePassword($user, "password"));
        $user2->setNbRequests(0);
        $user2->setNbRequestMax(100);
        $manager->persist($user2);

        $manager->flush();
    }
}
