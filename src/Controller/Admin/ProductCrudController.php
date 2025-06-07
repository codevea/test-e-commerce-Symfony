<?php

namespace App\Controller\Admin;

use App\Entity\Product;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\AssociationField;
use EasyCorp\Bundle\EasyAdminBundle\Field\ChoiceField;
use EasyCorp\Bundle\EasyAdminBundle\Field\IdField;
use EasyCorp\Bundle\EasyAdminBundle\Field\ImageField;
use EasyCorp\Bundle\EasyAdminBundle\Field\MoneyField;
use EasyCorp\Bundle\EasyAdminBundle\Field\SlugField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextEditorField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;

class ProductCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return Product::class;
    }


    public function configureFields(string $pageName): iterable
    {
        $require = ($pageName === 'edit') ? false : true;

        return [
            yield ImageField::new('illustration', 'Illustration')->setRequired($require)->setBasePath('uploads/')->setUploadDir('public/uploads/')->setUploadedFileNamePattern('[year]-[month]-[day]-[slug].[extension]'),
            yield TextField::new('name', 'Nom du produit'),
            yield SlugField::new('slug')->setTargetFieldName('name')->setHelp('Le slug, qui est une partie de l\'URL d\'une page, est créé automatiquement. Vous pouvez le modifier, mais faites attention : si des moteurs de recherche l\'ont déjà enregistré, changer le slug pourrait rendre la page introuvable et provoquer une erreur.'),
            yield TextEditorField::new('description'),
            yield MoneyField::new('price', 'Prix HT')->setCurrency('EUR'),
            yield ChoiceField::new('tva', 'TVA')->setChoices([
                '7,5%' => '7.5',
                '10%' => '10',
                '20%' => '20',
            ]),
            yield AssociationField::new('category', 'Catégories')
        ];
    }
}
