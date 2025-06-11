<?php

namespace App\Form;

use App\Entity\User;
use Symfony\Component\Form\FormError;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\RepeatedType;

class PasswordModifyUserAccountForm extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('pwd', PasswordType::class, [
                'label' => 'Veuillez saisir votre mot de passe actuel',
                'hash_property_path' => 'password',
                'mapped' => false,
            ])
            ->add('plainPassword', RepeatedType::class, [
                'type' => PasswordType::class,
                'first_options'  => ['label' => 'Nouveau mot de passe', 'hash_property_path' => 'password'],
                'second_options' => ['label' => 'Repeter votre nouveau mot de passe'],
                'mapped' => false,
            ])
            ->add('submit', SubmitType::class)
            ->addEventListener(FormEvents::SUBMIT, function (FormEvent $event) {
                $form = $event->getForm();
                $user = $form->getConfig()->getOptions()['data'];
                $userPasswordHasherInterface = $form->getConfig()->getOptions()['userPasswordHasherInterface'];

                // Compare le mot de passe entré par l'utilisateur avec le mot de passe enregistré dans la base de données.
                // Compare the password entered by the user with the password stored in the database.
                $isValid = $userPasswordHasherInterface->isPasswordValid(
                    $user,
                    $form->get('pwd')->getData() // Password en clair
                );

                if (!$isValid) {
                    $form->get('pwd')->addError(new FormError('Votre mot de passe n\'est pas conforme.'));
                }
            })
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => User::class,
            'userPasswordHasherInterface' => null
        ]);
    }
}
