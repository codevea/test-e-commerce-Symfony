<?php

namespace App\classe;

use Symfony\Component\HttpFoundation\RequestStack;

class Cart
{
  public function __construct(private RequestStack $requestStack) {}

  public function add($product)
  {
    // Récupère le panier en cours
    $cart = $this->requestStack->getSession()->get('cart');

    if (isset($cart[$product->getId()])) {
      $cart[$product->getId()]['quantity'] += 1;
    } else {
      $cart[$product->getId()] = [
        'object' => $product,
        'quantity' => 1,
      ];
    }

    // Panier actuel.
    $this->requestStack->getSession()->set('cart', $cart);
  }

  public function decrease($id)
  {
    // Récupère le panier en cours
    $cart = $this->requestStack->getSession()->get('cart');

    if ($cart[$id]['quantity'] > 1) {
      $cart[$id]['quantity'] = $cart[$id]['quantity'] - 1;
    } else {
      unset($cart[$id]);
    }

    // Panier actuel.
    $this->requestStack->getSession()->set('cart', $cart);
  }

  public function getCart()
  {
    // Récupère le panier en cours
    return $this->requestStack->getSession()->get('cart');
       
  }

  /**
   * Vide le panier
   * remove cart
   *
   * @return void
   */
  public function remove()
  {
    return $this->requestStack->getSession()->remove('cart');
  }


  public function getFullPriceWT()
  {
    // Récupère le panier en cours
    $cart = $this->requestStack->getSession()->get('cart');
    $totalPrice = 0;

    foreach ($cart as $product) {
      $totalPrice = $totalPrice + (($product['object']->getPriceWT() * $product['quantity']));
    };

    return $totalPrice;
  }

  /**
   * Calcule le prix total des articles hors taxes (HT).
   * Calculate the total price of items before tax (HT).
   *
   * @return $totalPriceHt;
   */
  public function getFullPriceHT()
  {
    // Récupère le panier en cours
    $cart = $this->requestStack->getSession()->get('cart');
    $totalPriceHt = 0;

    foreach ($cart as $product) {
      $totalPriceHt = $totalPriceHt + (($product['object']->getPrice() * $product['quantity']) / 100);
    };

    return $totalPriceHt;
  }

  /**
   * Retourne la quantité des produits dans le panier.
   * Return the quantity of products in the cart.
   *
   * @return $quantity;
   */
  public function fullQuantityProduct()
  {
    // Récupère le panier en cours
    $cart = $this->requestStack->getSession()->get('cart');
    $quantity = 0;

    if($quantity == 0){
      return  $quantity;
    }

    // Boucle sur le nombre de produit du panier
    foreach ($cart as $product) {
      $quantity = $quantity + $product['quantity'];
    };

    return $quantity;
  }
}
