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
use EasyCorp\Bundle\EasyAdminBundle\Context\AdminContext;
use EasyCorp\Bundle\EasyAdminBundle\Attribute\AdminAction;
use EasyCorp\Bundle\EasyAdminBundle\Field\AssociationField;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;

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
        // Création d'une vue détaillée de la commande personnalisée.
        $show = Action::new('Consulter')->linkToCrudAction('show');

        return $actions
            ->add(Crud::PAGE_INDEX, $show)
            ->remove(Crud::PAGE_INDEX, Action::NEW)
            ->remove(Crud::PAGE_INDEX, Action::DELETE)
            ->remove(Crud::PAGE_INDEX, Action::EDIT)
        ;
    }

    // Récupère la commande en cours et retourne la vue personnalisée affichant le détail de la commande.
    // https://symfony.com/bundles/EasyAdminBundle/current/actions.html#generating-dynamic-action-labels
    #[AdminAction(routePath: '/consulter', routeName: 'view_show', methods: ['GET', 'POST'])]
    public function show(AdminContext $context)
    {
        $order = $context->getEntity()->getInstance();;
      
         return $this->render('admin/order.html.twig', [
            'order' => $order,
        ]);
    }

    public function configureFields(string $pageName): iterable
    {
        return [
            yield AssociationField::new('user', 'Client')->setCrudController(UserCrudController::class),
            yield IdField::new('id', 'Commande'),
            yield DateField::new('createdAt', 'Date'),
            yield ChoiceField::new('state', 'Statut')->autocomplete()->setChoices([
                'En attente de paiement' => 1,
                'Commande payée' => 2,
                'Commande expédiée' => 3,
            ])->renderAsBadges([
                1 => 'warning',
                2 => 'success',
                3 => 'primary',
            ]),
            yield TextField::new('carrierName', 'Transporteur'),
            yield MoneyField::new('carrierPrice', 'Frais transport')->setCurrency('EUR'),
            yield MoneyField::new('getTotalTva', 'TVA')->setCurrency('EUR'),
            yield MoneyField::new('getTotalTtc', 'Total TTC')->setCurrency('EUR'),
        ];
    }
}
