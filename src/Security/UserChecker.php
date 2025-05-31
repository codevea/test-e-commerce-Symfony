<?php

namespace App\Security;

use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Security\Core\Exception\CustomUserMessageAccountStatusException;
use Symfony\Component\Security\Core\User\UserCheckerInterface;

// Avec cette méthode, l’utilisateur devra valider son e-mail avant de pouvoir se connecter.
// security.yaml + class UserChecker pour bloquer les utilisateurs non vérifiés :
//      main:
//          user_checker: App\Security\UserChecker
// OU dans page.html.twig
// if (!$user->isVerified()) {
//     $this->addFlash('error', 'Veuillez vérifier votre e-mail avant de continuer.');
//     return $this->redirectToRoute('app_verify_email');
// }
class UserChecker implements UserCheckerInterface
{
    public function checkPreAuth(UserInterface $user): void
    {
        if (!$user instanceof \App\Entity\User) {
            return;
        }

        if (!$user->isVerified()) {
            throw new CustomUserMessageAccountStatusException('Vous devez vérifier votre e-mail avant de vous connecter.');
        }
    }

    public function checkPostAuth(UserInterface $user): void
    {
        // Pas nécessaire ici
    }
}
