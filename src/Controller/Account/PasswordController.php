<?php

namespace App\Controller\Account;


use Doctrine\ORM\EntityManagerInterface;
use App\Form\PasswordModifyUserAccountForm;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

final class PasswordController extends AbstractController
{
    #[Route('/profil/modifier-le-mot-de-passe', name: 'app_account_modify_password_user')]
    public function update(Request $request, EntityManagerInterface $entityManagerInterface, UserPasswordHasherInterface $userPasswordHasherInterface): Response
    {
        $user = $this->getUser();

        $form = $this->createForm(PasswordModifyUserAccountForm::class, $user, ['userPasswordHasherInterface' => $userPasswordHasherInterface]);
        $form->handleRequest($request);

        if ($form->isSubmitted() and $form->isValid()) {
            $entityManagerInterface->flush();

            $this->addFlash('success', 'Votre mot de passe à bien été modifier');
            return $this->redirectToRoute('app_account');
        }

        return $this->render('account/password/password-modify-user-account.html.twig', [
            'form' => $form,
        ]);
    }
}
