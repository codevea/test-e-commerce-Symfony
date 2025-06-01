<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\ModifyPasswordUserForm;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

final class ModifyPasswordUserController extends AbstractController
{
    #[Route('/profil/modifier-le-mot-de-passe', name: 'app_account_modify_password_user')]
    public function index(Request $request, EntityManagerInterface $entityManagerInterface, UserPasswordHasherInterface $userPasswordHasherInterface): Response
    {
        $user = $this->getUser();

        $form = $this->createForm(ModifyPasswordUserForm::class, $user, ['userPasswordHasherInterface' => $userPasswordHasherInterface]);
        $form->handleRequest($request);

        if($form->isSubmitted() and $form->isValid() ){
            $entityManagerInterface->flush();
        }

        return $this->render('account/modify_password_user/index.html.twig', [
            'form' => $form,
        ]);
    }
}
