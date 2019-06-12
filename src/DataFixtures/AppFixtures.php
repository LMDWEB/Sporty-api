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

        $contentTeamsPremierLeague = file_get_contents(__DIR__.'/../../public/TeamPremierLeague.json');
        $contentTeamsPremierLeague = json_decode($contentTeamsPremierLeague);

        $teamsBundesliga = json_decode(file_get_contents(__DIR__.'/../../public/TeamBundesliga.json'));

        $contentPlayers = file_get_contents(__DIR__.'/../../public/PsgPlayers.json');
        $contentPlayers = json_decode($contentPlayers);

        $contentMatchLigue1 = file_get_contents(__DIR__.'/../../public/MatchLigue1.json');
        $contentMatchLigue1 = json_decode($contentMatchLigue1);


        $leagues = array();

        /*
         * LEAGUE
         */

        foreach ($contentLeagues->api->leagues as $league){
            if (
                $league->name == "Ligue 1" && $league->country == "France" && $league->season == 2018 ||
                $league->name == "Premier League" && $league->country == "England" && $league->season == 2018 ||
                $league->name == "Bundesliga 1" && $league->country == "Germany" && $league->season == 2018

            ){
                $leagueData = new League();
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
                $leagues[$league->league_id] = $leagueData;
            }
        }

        $psgTeam = "";
        $teams = array();

        /*
         * TEAM
         */

        foreach ($contentTeams->api->teams as $team){
            $teamData = new Team();
            $teamData->setName($team->name)
                ->setCode($team->code)
                ->setLogo($team->logo)
                ->setCountry($team->country)
                ->setFounded($team->founded)
                ->setVenueName($team->venue_name)
                ->setVenueCapacity($team->venue_capacity)
                ->setLeague($leagues[4]);
            $manager->persist($teamData);

            if ($teamData->getCode() == "PSG"){
                $psgTeam = $teamData;
            }
            $teams[$team->team_id] = $teamData;
        }

        foreach ($contentTeamsPremierLeague->api->teams as $team){
            $teamData = new Team();
            if ($team->code == null) $team->code = "";
            $teamData->setName($team->name)
                ->setCode($team->code)
                ->setLogo($team->logo)
                ->setCountry($team->country)
                ->setFounded($team->founded)
                ->setVenueName($team->venue_name)
                ->setVenueCapacity($team->venue_capacity)
                ->setLeague($leagues[2]);
            $manager->persist($teamData);

        }

        foreach ($teamsBundesliga->api->teams as $team){
            $teamData = new Team();
            if ($team->code == null) $team->code = "";
            $teamData->setName($team->name)
                ->setCode($team->code)
                ->setLogo($team->logo)
                ->setCountry($team->country)
                ->setFounded($team->founded)
                ->setVenueName($team->venue_name)
                ->setVenueCapacity($team->venue_capacity)
                ->setLeague($leagues[8]);
            $manager->persist($teamData);

        }

        /*
         * PLAYER
         */

        foreach ($contentPlayers->api->players as $player){
            $playerData = new Player();
            $playerData->setTeam($psgTeam)
                ->setName($player->player_name)
                ->setNumber($player->number)
                ->setAge($player->age)
                ->setPosition($player->position);
            $manager->persist($playerData);
        }

        /*
         * GAME
         */
        foreach ($contentMatchLigue1->api->fixtures as $match){
            $game = new Game();
            $game->setLeague($leagues[4])
                ->setStatus($match->status)
                ->setEventTimestamp($match->event_timestamp)
                ->setAwayTeam($teams[$match->awayTeam_id])
                ->setHomeTeam($teams[$match->homeTeam_id])
                ->setGoalsAwayTeam($match->goalsAwayTeam)
                ->setGoalsHomeTeam($match->goalsHomeTeam)
                ->setScore($match->final_score);

            $manager->persist($game);
        }

        $manager->flush();
    }
}
