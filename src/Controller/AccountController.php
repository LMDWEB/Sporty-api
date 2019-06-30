<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;
use Symfony\Component\HttpFoundation\Request;

class AccountController extends AbstractController
{
    /**
     * @Route("/user/login", name="account_login")
     * @param AuthenticationUtils $utils
     * @return Response
     */
    public function login(AuthenticationUtils $utils, Request $request)
    {
        $error = $utils->getLastAuthenticationError();
        $username = $utils->getLastUsername();

        return $this->render('account/login.html.twig', [
            'hasError' => $error !== null,
            'username' => $username,
            'locale' => $request->getLocale()
        ]);
    }

    /**
     * @Route("/user/logout", name="account_logout")
     */
    public function logout(){

    }

    /**
     * @Route("/account", name="my_account")
     */
    public function my_account(){
        return $this->render('account/account.html.twig');
    }
}
