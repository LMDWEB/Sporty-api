<?php

namespace App\DataFixtures;

use App\Entity\Club;
use App\Entity\Player;
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
        $joueurs = json_decode(file_get_contents("https://raw.githubusercontent.com/LMDWEB/Sporty/master/public/json/playerL1.json"));

        $clubs = array();
        $realClubs = [];

        foreach ($joueurs as $joueur){
            $clubs[$joueur->{'Current club'}] = $joueur->{'Current club'};
        }

        foreach($clubs as $club){
            $team = new Club();
            $team->setName($club);
            $manager->persist($team);
            $realClubs[$team->getName()] = $team;
        }

        foreach ($joueurs as $joueur) {
            $foot = (isset($joueur->Foot)) ? $joueur->Foot : "right";
            $club = $realClubs[$joueur->{'Current club'}];
            if(!isset($joueur->Height)) {$height = 0;} else {$height = $joueur->Height;}
            $height = str_replace(',', '.', $height);
            $height = str_replace('m', '', $height);
            $height = str_replace(' ', '', $height);
            $height = floatval($height);

            $player = new Player();

            $player->setFullName($joueur->Name)
                ->setPosition($joueur->Position)
                ->setHeight($height)
                ->setBirthdayDate(time())
                ->setAge($joueur->Age)
                ->setFoot($foot)
                ->setClub($club);

            $manager->persist($player);
        }

        $adminRole = new Role();
        $adminRole->setTitle("ROLE_ADMIN");
        $manager->persist($adminRole);

        $freeRole = new Role();
        $freeRole->setTitle("ROLE_FREE");
        $manager->persist($freeRole);

        $proRole = new Role();
        $proRole->setTitle("ROLE_PRO");
        $manager->persist($proRole);

        $entrepriseRole = new Role();
        $entrepriseRole->setTitle("ROLE_ENTREPRISE");
        $entrepriseRole->persist($entrepriseRole);

        $user = new User("admin");
        $user->setPassword($this->encoder->encodePassword($user, "password"));
        $user->addUserRole($adminRole);
        $manager->persist($user);

        $user2 = new User("random");
        $user2->setPassword($this->encoder->encodePassword($user, "password"));
        $manager->persist($user2);

        $manager->flush();
    }
}
