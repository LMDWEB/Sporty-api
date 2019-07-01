<?php

// tests/Util/CalculatorTest.php
namespace App\Tests\Util;

use App\Entity\Comment;
use App\Entity\Game;
use App\Entity\GameSuggest;
use App\Entity\User;
use App\Util\Calculator;
use PHPUnit\Framework\TestCase;

class CalculatorTest extends TestCase
{
    public function testAdd()
    {
        $calculator = new Calculator();
        $result = $calculator->add(30, 12);

        // assert that your calculator added the numbers correctly!
        $this->assertEquals(42, $result);
    }

    public function testCreatorOfComment(){
        $comment = new Comment();
        $user = new User();
        $comment->setCreator($user);

        $this->assertEquals(get_class($user), get_class($comment->getCreator()));
    }

    public function testCreatorOfGameSuggest(){
        $suggest = new GameSuggest();
        $user = new User();
        $suggest->setAuthor($user);

        $this->assertEquals(get_class($user), get_class($suggest->getAuthor()));
    }

    public function testGameOfGameSuggest(){
        $suggest = new GameSuggest();
        $game = new Game();
        $suggest->setGame($game);

        $this->assertEquals(get_class($game), get_class($suggest->getGame()));
    }

}