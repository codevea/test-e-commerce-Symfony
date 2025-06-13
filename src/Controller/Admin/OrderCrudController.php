<?php

namespace App\Controller\Admin;

use App\Entity\Order;
use App\Controller\Admin\UserCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;
use EasyCorp\Bundle\EasyAdminBundle\Config\Action;
use EasyCorp\Bundle\EasyAdminBundle\Field\IdField;
use EasyCorp\Bundle\EasyAdminBundle\Config\Actions;
use EasyCorp\Bundle\EasyAdminBundle\Field\DateField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;
use EasyCorp\Bundle\EasyAdminBundle\Field\MoneyField;
use EasyCorp\Bundle\EasyAdminBundle\Field\ChoiceField;
use EasyCorp\Bundle\EasyAdminBundle\Field\AssociationField;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\NumberField;

class OrderCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return Order::class;
    }

    public function configureCrud(Crud $crud): Crud
    {
        return $crud
            ->setEntityLabelInSingular('commande')
            ->setEntityLabelInPlural('commandes')
        ;
    }

    public function configureActions(Actions $actions): Actions
    {
        return $actions
            ->remove(Crud::PAGE_INDEX, Action::NEW)
            ->remove(Crud::PAGE_INDEX, Action::DELETE)
            ->remove(Crud::PAGE_INDEX, Action::EDIT)
            ->add(Crud::PAGE_INDEX, Action::DETAIL)
        ;
    }


    public function configureFields(string $pageName): iterable
    {
        return [
            yield AssociationField::new('user', 'Client')->setCrudController(UserCrudController::class),
            yield IdField::new('id', 'Commande'),
            yield DateField::new('createdAt', 'Date'),
            yield ChoiceField::new('state', 'Statut')->autocomplete()->setChoices([
                'En attente de paiement' => 1,
                'payée' => 2,
                'Expédiée' => 3,
            ])->renderAsBadges([
                1 => 'primary',
                2 => 'danger',
                3 => 'success',
            ]),
            yield TextField::new('carrierName', 'Transporteur'),
            yield MoneyField::new('carrierPrice', 'Frais transport')->setCurrency('EUR'),
            yield MoneyField::new('getTotalTva', 'TVA')->setCurrency('EUR'),
            yield MoneyField::new('getTotalTtc', 'Total TTC')->setCurrency('EUR'),
        ];
    }
}
