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
        //test

        $tab = array();

        foreach ($games as $game){
            $tab[] = array(
              'id' => $game->getId(),
              'status' => $game->getStatus(),
              'homeTeam' => array(
                  'id' => $game->getHomeTeam()->getId(),
                  'name' => $game->getHomeTeam()->getName(),
                  'logo' => $game->getHomeTeam()->getLogo()
              ),
              'awayTeam' => array(
                  'id' => $game->getAwayTeam()->getId(),
                  'name' => $game->getAwayTeam()->getName(),
                  'logo' => $game->getAwayTeam()->getLogo()
              ),
              'league' => array(
                  'id' => $game->getLeague()->getId(),
                  'name' => $game->getLeague()->getName()
              )
            );
        }

        //dd($tab);
        //die();

        $response->setContent(json_encode($tab));

        $response->headers->set('Content-Type', 'application/json');
        return $response;
    }
}
