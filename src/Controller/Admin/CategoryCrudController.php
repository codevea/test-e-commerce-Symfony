<?php

namespace App\Controller\Admin;

use App\Entity\Category;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\SlugField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;

class CategoryCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return Category::class;
    }

    public function configureCrud(Crud $crud): Crud {
        return $crud
            ->setEntityLabelInSingular('catégorie')
            ->setEntityLabelInPlural('catégories')
        ;
    }

    public function configureFields(string $pageName): iterable
    {
        return [
            yield TextField::new('name', 'Catégorie'),
            yield SlugField::new('slug')->setTargetFieldName('name')->setHelp('Le slug, qui est une partie de l\'URL d\'une page, est créé automatiquement. Vous pouvez le modifier, mais faites attention : si des moteurs de recherche l\'ont déjà enregistré, changer le slug pourrait rendre la page introuvable et provoquer une erreur.')
        ];
    }
  
}
