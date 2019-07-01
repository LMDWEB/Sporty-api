<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

class GiftController extends AbstractController
{
    /**
     * @Route("/cadeaux", name="gift")
     */
    public function index()
    {
        return $this->render('gift/index.html.twig', [
            'controller_name' => 'GiftController',
        ]);
    }
}
