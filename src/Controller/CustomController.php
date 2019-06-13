<?php

namespace App\Controller;

use App\Entity\Game;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;


class CustomController extends AbstractController
{
    /**
     * @Route("/api/last_games", name="last_games")
     */
    public function last()
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
              ),
              'score' => $game->getScore(),
              'goalsAwayTeam' => $game->getGoalsAwayTeam(),
              'goalsHomeTeam' => $game->getGoalsHomeTeam(),
              'eventStart' => $game->getEventStart(),
              'round' => $game->getRound()
            );
        }

        //dd($tab);
        //die();

        $response->setContent(json_encode($tab));

        $response->headers->set('Content-Type', 'application/json');
        return $response;
    }

    /**
     * @Route("/api/current_games", name="current_games")
     */
    public function current()
    {
        $response = new Response();

        $games = $this->getDoctrine()->getRepository(Game::class)
            ->findFiveLastGames();


        //dd($games);
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
                ),
                'score' => $game->getScore(),
                'goalsAwayTeam' => $game->getGoalsAwayTeam(),
                'goalsHomeTeam' => $game->getGoalsHomeTeam(),
                'eventStart' => $game->getEventStart(),
                'round' => $game->getRound()
            );
        }

        //dd($tab);
        //die();

        $response->setContent(json_encode($tab));

        $response->headers->set('Content-Type', 'application/json');
        return $response;
    }

    /**
     * @Route("/api/next_games", name="next_games")
     */
    public function next()
    {
        $response = new Response();

        $games = $this->getDoctrine()->getRepository(Game::class)
            ->findFiveNextGames();

       // dd($games);

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
                ),
                'score' => $game->getScore(),
                'goalsAwayTeam' => $game->getGoalsAwayTeam(),
                'goalsHomeTeam' => $game->getGoalsHomeTeam(),
                'eventStart' => $game->getEventStart(),
                'round' => $game->getRound()
            );
        }

        //dd($tab);
        //die();

        $response->setContent(json_encode($tab));

        $response->headers->set('Content-Type', 'application/json');
        return $response;
    }

    /**
     * @Route("/api/me", name="me")
     */
    public function me(TokenStorageInterface $storage)
    {
        $response = new Response();
        $token = $storage->getToken();
        $user = $token->getUser();
        $name = $user->getUsername();
        $id = $user->getId();

        $response->setContent(json_encode([
            'id' => $id,
            'name' => $name
        ]));
        $response->headers->set('Content-Type', 'application/json');

        return $response;
    }
}
