<?php

namespace App\Form;

use App\Entity\Address;
use App\Entity\Carrier;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class OrderForm extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('address', EntityType::class, [
                'label' => 'Choix de l\'adresse de livraison',
                'required' => true,
                'class' => Address::class,
                'choices' => $options['addresses']
            ])
            ->add('carrier', EntityType::class, [
                'label' => 'Choix de l\'adresse du transporteur',
                'required' => true,
                'class' => Carrier::class,
                'expanded' => true,
                'label_html' => true,
                'attr' => [
                    'class' => 'p-3 bg-light my-5'
                ],
            ])
            ->add('submit', SubmitType::class, [
                'label' => "Valider ma commande",
                'attr' => [
                    'class' => 'btn btn-primary'
                ],
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'addresses' => null
        ]);
    }
}
