<?php

namespace App\Controller;

use App\Entity\Game;
use App\Repository\GameRepository;
use App\Repository\GameSuggestRepository;
use Doctrine\Common\Persistence\ManagerRegistry;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;


class CustomController extends AbstractController
{
    private $tokenStorage;
    private $manager;

    public function __construct(ManagerRegistry $manager, TokenStorageInterface $storage)
    {
        $this->manager = $manager->getManager();
        $this->tokenStorage = $storage;
    }

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

        $games = $this->getDoctrine()->getRepository(Game::class)->findBy(array('status' => 'in progress'), null, 5);


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

        $games = $this->getDoctrine()->getRepository(Game::class)->findBy(array('status' => 'coming'), null, 5);

       // dd($games);

        //$response->setContent($games);
        //test

        $tab = array();

        foreach ($games as $game){
            if ($game->getScore() == "0-0") $score = null; else $score = $game->getScore();
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
                'score' => $score,
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
     * @Route("/api/next_game", name="next_game")
     */
    public function nextgame()
    {
        $response = new Response();

        $game = $this->getDoctrine()->getRepository(Game::class)->findOneBy([], array('id' => 'DESC'), 1, 1);

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

    /**
     * @Route("/api/suggest/{id}", name="suggest")
     * @param $id
     * @param GameSuggestRepository $gameSuggestRepository
     * @return Response
     */
    public function scoreSuggest($id, GameSuggestRepository $gameSuggestRepository, GameRepository $gameRepository)
    {
        $response = new Response();

        $game = $gameRepository->find($id);

        $games = $gameSuggestRepository->findByGame($game->getId());

        if(count($games) > 0){
            $result = array();
            foreach ($games as $g){
                $result[$g->getAuthor()->getId()] = $g->getScoreHomeTeam().'-'.$g->getScoreAwayTeam();
            }

            $counts = array_count_values($result);
            arsort($counts);
            $top_with_count = array_slice($counts, 0, 1, true);

            $score = explode('-', key($top_with_count));

            $response->setContent(json_encode(array(
                'id' => $id,
                'game' => '/api/games/'.$id,
                'accuracy' => current($top_with_count) / count($result) * 100,
                'scoreHomeTeam' => $score[0],
                'scoreAwayTeam' => $score[1],
                'contributors' => count($result)

            )));
        } else {
            $response->setContent(json_encode(array(
                'id' => $id,
                'game' => '/api/games/'.$id,
                'accuracy' => 0,
                'scoreHomeTeam' => null,
                'scoreAwayTeam' => null,
                'contributors' => 0

            )));
        }


        $response->headers->set('Content-Type', 'application/json');

        return $response;
    }

    /**
     * @Route("/api/lastsuggestbygame/{idgame}")
     * @param $idgame
     */
    public function lastScoreSuggestByUser($idgame, GameSuggestRepository $gameSuggestRepository){

        $token = $this->tokenStorage->getToken();
        $user = $token->getUser();


        $games = $gameSuggestRepository->findByGame($idgame);

        $response = new Response();

        if(count($games) > 0){

            $result = "";

            foreach ($games as $g){
                if ($g->getAuthor()->getId() == $user->getId()){
                    $result = array(
                        "id" => $g->getId(),
                        "game" => "/api/games/".$idgame,
                        "author"=> "/api/users/".$g->getAuthor()->getUsername(),
                        "scoreHomeTeam"=> $g->getScoreHomeTeam(),
                        "scoreAwayTeam" => $g->getScoreAwayTeam(),
                        "createdAt" => $g->getCreatedAt(),
                        "isValid" => 0
                    );
                }
            }

            if ($result == ""){
                $result = array(
                    "id" => null,
                    "game" => "/api/games/".$idgame,
                    "author"=> "/api/users/".$user->getUsername(),
                    "scoreHomeTeam"=> null,
                    "scoreAwayTeam" => null,
                    "createdAt" => null,
                    "isValid" => 0
                );
            }


            $response->setContent(json_encode($result));
        } else {
            $response->setContent(json_encode(array(
                "id" => null,
                "game" => "/api/games/".$idgame,
                "author"=> "/api/users/".$user->getUsername(),
                "scoreHomeTeam"=> null,
                "scoreAwayTeam" => null,
                "createdAt" => null,
                "isValid" => 0
            )));
        }

        $response->headers->set('Content-Type', 'application/json');

        return $response;
    }
}
