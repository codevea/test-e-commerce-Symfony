<?php

namespace App\classe;

use Symfony\Component\HttpFoundation\RequestStack;

class Cart
{
  /**
   * panier en cours
   *
   * @return getCart()
   */
  public function getCart()
  {
    // Récupère le panier en cours
    return $this->requestStack->getSession()->get('cart');
  }

  public function __construct(private RequestStack $requestStack) {}

  /**
   * Fonction permettant d'ajouter un produit au panier.
   * Function allowing to add a product to the cart
   *
   * @param [type] $product
   * @return void
   */
  public function addProductCart($product)
  {
    // Appel de la fonction getCart()
    // Call of the function getCart()
    $cart = $this->getCart();

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

  /**
   * Décremente le nombre de produit dans le panier
   * Decreases the number of products in the cart.
   *
   * @param [type] $id
   * @return void
   */
  public function decreaseNumberProductCart($id)
  {
    // Appel de la fonction getCart();
    // Call of the function getCart()
    $cart = $this->getCart();

    if ($cart[$id]['quantity'] > 1) {
      $cart[$id]['quantity'] = $cart[$id]['quantity'] - 1;
    } else {
      unset($cart[$id]);
    }

    // Panier actuel.
    $this->requestStack->getSession()->set('cart', $cart);
  }

  /**
   * Vide le panier
   * remove cart
   *
   * @return void
   */
  public function removeCart()
  {
    return $this->requestStack->getSession()->remove('cart');
  }

  /**
   * Calcule le prix total des articles (TTC).
   * Calculate the total price of items tax (TTC).
   *
   * @return  $totalPrice;
   */
  public function allPriceTtc()
  {
    // Appel de la fonction getCart()
    // Call of the function getCart()
    $cart = $this->getCart();
    $totalPriceTtc = 0;

    if (!isset($cart)) {
      return $totalPriceTtc;
    }

    foreach ($cart as $product) {
      $totalPriceTtc = $totalPriceTtc + ($product['object']->getProductTtc() * $product['quantity']);
    };

    return $totalPriceTtc;
  }

  /**
   * Calcule le prix total des articles hors taxes (HT).
   * Calculate the total price of items before tax (HT).
   *
   * @return $totalPriceHt;
   */
  public function allPriceHt()
  {
    // Appel à la fonction getCart()
    // Call of the function getCart().
    $cart = $this->getCart();
    $totalPriceHt = 0;

    if (!isset($cart)) {
      return $totalPriceHt;
    }

    foreach ($cart as $product) {
      $totalPriceHt = $totalPriceHt + (($product['object']->getPrice() * $product['quantity']) / 100);
    }

    return $totalPriceHt;
  }

  /**
   * Retourne la quantité des produits dans le panier.
   * Return the quantity of products in the cart.
   *
   * @return $quantity;
   */
  public function allCountQuantityProduct()
  {
    // Récupère la function getCart()
    $cart = $this->getCart();
    $quantity = 0;

    if (!isset($cart)) {
      return $quantity;
    }
    // Boucle sur le nombre de produit du panier
    foreach ($cart as $product) {
      $quantity = $quantity + $product['quantity'];
    };

    return $quantity;
  }
}
