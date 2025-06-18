<?php

namespace App\Controller\Cart;

use App\classe\Cart;
use App\Repository\ProductRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

final class CartController extends AbstractController
{
    #[Route('/mon-panier/{motif}', name: 'app_cart', defaults: ['motif' => null], schemes: ['https'])]
    public function cart(Cart $cart, $motif): Response
    {
        if ($motif) {
            $this->addFlash('info', 'Paiement annulé. Vous pouvez mettre à jour votre commande ou la supprimer.');
        }

        return $this->render('cart/cart.html.twig', [
            'cart' => $cart->getCart(),
            'allPriceTtc' => $cart->allPriceTtc(),
            'allPriceHt' => $cart->allPriceHt()
        ]);
    }

    #[Route('/mon-panier/ajoute-produit/{id}', name: 'app_cart_add', schemes: ['https'])]
    public function add($id, Cart $cart, ProductRepository $productRepository, Request $request): Response
    {
        $product = $productRepository->findOneById($id);
        $cart->addProductCart($product);


        $this->addFlash('success', 'Le produit à été ajouter à votre panier');
        return $this->redirect($request->headers->get('referer')); // Récupère l'URL de la dernière page visitée.
    }

    #[Route('/mon-panier/remove', name: 'app_cart_remove', schemes: ['https'])]
    public function remove(Cart $cart): Response
    {
        $cart->removeCart();
        $this->addFlash('success', 'Le pranier à bien été supprimer');
        return $this->redirectToRoute('app_home');
    }

    #[Route('/mon-panier/decrease-{id}', name: 'app_cart_decrease', schemes: ['https'])]
    public function decrease($id, Cart $cart)
    {
        $cart->decreaseNumberProductCart($id);
        $this->addFlash('success', 'Le produit à bien été supprimer à votre panier');
        return $this->redirectToRoute('app_cart');
    }
}
