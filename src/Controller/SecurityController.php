<?php

namespace App\Controller;

use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;

class SecurityController extends AbstractController
{
    #[Route(path: '/connexion', name: 'app_login', schemes: ['https'])]
    public function login(AuthenticationUtils $authenticationUtils): Response
    {

        // Redirige vers la page d'accueil    
        if ($this->getUser()) {
            return $this->redirectToRoute('app_home');
        }
        
        // get the login error if there is one
        $error = $authenticationUtils->getLastAuthenticationError();
        // last username entered by the user
        $lastUsername = $authenticationUtils->getLastUsername();

        return $this->render('security/login.html.twig', [
            'last_username' => $lastUsername,
            'error' => $error,
        ]);
    }

    #[Route(path: '/logout', name: 'app_logout', schemes: ['https'])]
    public function logout(): void
    {
        throw new \LogicException('Cette méthode peut être vide – elle sera interceptée par la clé de déconnexion de votre pare-feu.');
    }
}
