<?php

namespace App\DataFixtures;

use App\Entity\Game;
use App\Entity\League;
use App\Entity\Player;
use App\Entity\Role;
use App\Entity\Team;
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
        $contentLeagues = file_get_contents(__DIR__.'/../../public/Leagues.json');
        $contentLeagues = json_decode($contentLeagues);

        $contentTeams = file_get_contents(__DIR__.'/../../public/TeamLigue1.json');
        $contentTeams = json_decode($contentTeams);

        $contentPlayers = file_get_contents(__DIR__.'/../../public/PsgPlayers.json');
        $contentPlayers = json_decode($contentPlayers);

        $contentMatchLigue1 = file_get_contents(__DIR__.'/../../public/MatchLigue1.json');
        $contentMatchLigue1 = json_decode($contentMatchLigue1);

        $leagueData = new League();

        foreach ($contentLeagues->api->leagues as $league){
            if ($league->name == "Ligue 1" && $league->country == "France" && $league->season == 2018){

                $seasonStart = \DateTime::createFromFormat('Y-m-d', $league->season_start);
                $seasonEnd = \DateTime::createFromFormat('Y-m-d', $league->season_end);
                $leagueData->setName($league->name)
                    ->setCountry($league->country)
                    ->setCountryCode($league->country_code)
                    ->setSeason($league->season)
                    ->setSeasonStart($seasonStart)
                    ->setSeasonEnd($seasonEnd)
                    ->setLogo($league->logo)
                    ->setFlag($league->flag)
                    ->setIsCurrent($league->is_current);
                $manager->persist($leagueData);
            }
        }

        $psgTeam = "";
        $teams = array();

        foreach ($contentTeams->api->teams as $team){
            $teamData = new Team();
            $teamData->setName($team->name)
                ->setCode($team->code)
                ->setLogo($team->logo)
                ->setCountry($team->country)
                ->setFounded($team->founded)
                ->setVenueName($team->venue_name)
                ->setVenueCapacity($team->venue_capacity)
                ->setLeague($leagueData);
            $manager->persist($teamData);

            if ($teamData->getCode() == "PSG"){
                $psgTeam = $teamData;
            }
            $teams[$team->team_id] = $teamData;
        }

        foreach ($contentPlayers->api->players as $player){
            $playerData = new Player();
            $playerData->setTeam($psgTeam)
                ->setName($player->player_name)
                ->setNumber($player->number)
                ->setAge($player->age)
                ->setPosition($player->position);
            $manager->persist($playerData);
        }

        foreach ($contentMatchLigue1->api->fixtures as $match){
            $game = new Game();
            $game->setLeague($leagueData)
                ->setStatus($match->status)
                ->setEventTimestamp($match->event_timestamp)
                ->setAwayTeam($teams[$match->awayTeam_id])
                ->setHomeTeam($teams[$match->homeTeam_id]);
            $manager->persist($game);
        }




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
