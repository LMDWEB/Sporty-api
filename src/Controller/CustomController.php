<?php

namespace App\Controller;

use App\Entity\Game;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Response;


class CustomController extends AbstractController
{
    /**
     * @Route("/api/last_games", name="custom")
     */
    public function index()
    {
        $response = new Response();

        $games = $this->getDoctrine()->getRepository(Game::class)
            ->findFiveLastGames();

        //$response->setContent($games);

        $tab = array();

        foreach ($games as $game){
            $tab[] = array(
              'id' => $game->getId(),
              'homeTeam' => $game->getHomeTeam()->getName(),
              'homeTeamLogo' => $game->getHomeTeam()->getLogo(),
              'awayTeam' => $game->getAwayTeam()->getName(),
              'awayTeamLogo' => $game->getAwayTeam()->getLogo(),
              'score' => $game->getScore(),
              'goalsHomeTeam' => $game->getGoalsHomeTeam(),
              'goalsAwayTeam' => $game->getGoalsAwayTeam(),
              'eventStart' => $game->getEventStart(),
              'eventBegin' => $game->getEventBegin()
            );
        }

        //dd($tab);
        //die();

        $response->setContent(json_encode($tab));

        $response->headers->set('Content-Type', 'application/json');
        return $response;
    }
}
