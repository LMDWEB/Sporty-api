<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Dotenv\Dotenv;

class TokenController extends AbstractController
{

    /**
     * @Route("/token", name="token")
     */
    public function index()
    {
        $dotenv = new Dotenv();
        $dotenv->load(__DIR__.'/../../.env');
        $url = $_ENV['APP_URL'];


        return $this->render('token/index.html.twig', [
            'controller_name' => 'TokenController',
            'url' => $url
        ]);
    }
}
