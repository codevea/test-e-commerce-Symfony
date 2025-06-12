<?php

namespace App\Controller\Admin;

use App\Entity\Carrier;
use EasyCorp\Bundle\EasyAdminBundle\Field\IdField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;
use EasyCorp\Bundle\EasyAdminBundle\Field\MoneyField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextareaField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextEditorField;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;

class CarrierCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return Carrier::class;
    }

  
    public function configureFields(string $pageName): iterable
    {
        return [
            yield TextField::new('name', 'Nom du transporteur'),
            yield MoneyField::new('price', 'Prix T.T.C')->setCurrency('EUR'),
            yield TextareaField::new('description', 'Information sur le transporteur'),
        ];
    }
 
}
