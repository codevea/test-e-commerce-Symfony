<?php

namespace App\Controller;

use App\classe\Cart;
use App\Repository\ProductRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

final class CartController extends AbstractController
{
    #[Route('/mon-panier', name: 'app_cart')]
    public function index(Cart $cart): Response
    {
        // if (!empty($cart)) {
        //     $this->addFlash('info', 'Votre panier est vide');
        //     return  $this->redirectToRoute('app_home');
        // }

        return $this->render('cart/index.html.twig', [
            'cart' => $cart->getCart(),
            'allPriceTtc' => $cart->allPriceTtc(),
            'allPriceHt' => $cart->allPriceHt()
        ]);
    }

    #[Route('/cart/add-{id}', name: 'app_cart_add')]
    public function add($id, Cart $cart, ProductRepository $productRepository, Request $request): Response
    {
        $product = $productRepository->findOneById($id);
        $cart->add($product);

        $this->addFlash('success', 'Le produit à été ajouter à votre panier');
        // Récupère l'URL de la dernière page visitée.
        return $this->redirect($request->headers->get('referer'));
    }

    #[Route('cart/remove', name: 'app_cart_remove')]
    public function remove(Cart $cart): Response
    {
        $cart->remove();
        $this->addFlash('success', 'Le pranier à bien été supprimer');
        return $this->redirectToRoute('app_home');
    }

    #[Route('cart/decrease-{id}', name: 'app_cart_decrease')]
    public function decrease($id, Cart $cart)
    {

        $cart->decrease($id);
        $this->addFlash('success', 'Le produit à bien été supprimer à votre panier');
        return $this->redirectToRoute('app_cart');
    }
}
