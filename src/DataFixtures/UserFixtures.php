<?php

namespace App\DataFixtures;

use App\Entity\Role;
use App\Entity\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class UserFixtures extends Fixture
{
    public function __construct(UserPasswordEncoderInterface $encoder)
    {
        $this->encoder = $encoder;
    }

    public function load(ObjectManager $manager)
    {
        /*
         * ROLE
         */
        $adminRole = new Role();
        $adminRole->setTitle("ROLE_ADMIN");
        $manager->persist($adminRole);

        $freeRole = new Role();
        $freeRole->setTitle("ROLE_API");
        $manager->persist($freeRole);

        $proRole = new Role();
        $proRole->setTitle("ROLE_API_PRO");
        $manager->persist($proRole);

        $companyRole = new Role();
        $companyRole->setTitle("ROLE_API_COMPANY");
        $manager->persist($companyRole);

        /*
        * USER
        */
        $user = new User();
        $user->setUsername("admin");
        $user->setEmail("admin@admin.fr");
        $user->setPassword($this->encoder->encodePassword($user, "password"));
        $user->addUserRole($adminRole);
        $user->setNbRequests(0);
        $user->setNbRequestMax(99999);
        $manager->persist($user);

        $user2 = new User();
        $user2->setUsername("random");
        $user2->setEmail("random@random.fr");
        $user2->setPassword($this->encoder->encodePassword($user, "password"));
        $user2->setNbRequests(0);
        $user2->setNbRequestMax(100);
        $manager->persist($user2);

        $user3 = new User();
        $user3->setUsername("toto");
        $user3->setEmail("toto@toto.fr");
        $user3->setPassword($this->encoder->encodePassword($user, "toto"));
        $user3->setNbRequests(0);
        $user3->setNbRequestMax(100);
        $manager->persist($user3);

        $user4 = new User();
        $user4->setUsername("tata");
        $user4->setEmail("tata@tata.fr");
        $user4->setPassword($this->encoder->encodePassword($user, "tata"));
        $user4->setNbRequests(0);
        $user4->setNbRequestMax(100);
        $manager->persist($user4);

        $manager->flush();
    }
}
