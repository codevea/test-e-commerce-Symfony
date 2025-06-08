<?php

namespace App\Twig;

use App\classe\Cart;
use Twig\Extension\GlobalsInterface;
use Twig\Extension\AbstractExtension;
use App\Repository\CategoryRepository;

class AppExtensions extends AbstractExtension implements GlobalsInterface
{
  public function __construct(private CategoryRepository $categoryRepository, private Cart $cart)
  {
    $this->categoryRepository = $categoryRepository;
    $this->cart = $cart;
  }

  public function getGlobals(): array
  {
    return [
      // Récupère toutes les catégories
      'allCategory' => $this->categoryRepository->findAll(),
      // Récupère la quantitées total des produits dans le panier
      'fullQuantityProduct' => $this->cart->fullQuantityProduct(),
    ];
  }
}
