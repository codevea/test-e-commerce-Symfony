<?php

namespace App\Controller\Account;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

final class HomeController extends AbstractController
{
    #[Route('/profil/mon-compte', name: 'app_account')]
    public function index(): Response
    {
        return $this->render('account/homeAccount.html.twig');
    }
}
