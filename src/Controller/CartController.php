<?php

namespace App\Controller;

use App\classe\Cart;
use App\Repository\OrderDetailRepository;
use App\Repository\ProductRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

final class CartController extends AbstractController
{
    #[Route('/mon-panier-{motif}', name: 'app_cart', defaults: ['motif' => null])]
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

    #[Route('/cart/add-{id}', name: 'app_cart_add')]
    public function add($id, Cart $cart, ProductRepository $productRepository, Request $request, OrderDetailRepository $orderDetailRepository): Response
    {
        $product = $productRepository->findOneById($id);
        // $orderDetail = $orderDetailRepository->findOneByMyOrder('myOrder');
        $cart->addProductCart($product);

        //     if($product->getStock() > 0) {
        //          $product = $product->setStock($product->getStock()) - $orderDetail->getProductQuantity();
        //     } else {
        //          $this->addFlash('info', 'Le produit n\'est plus disponible');
        //         return $this->redirect($request->headers->get('referer'));
        //     }
        //      $cart->addProductCart($product);

        $this->addFlash('success', 'Le produit à été ajouter à votre panier');
        // Récupère l'URL de la dernière page visitée.
        return $this->redirect($request->headers->get('referer'));
    }

    #[Route('cart/remove', name: 'app_cart_remove')]
    public function remove(Cart $cart): Response
    {
        $cart->removeCart();
        $this->addFlash('success', 'Le pranier à bien été supprimer');
        return $this->redirectToRoute('app_home');
    }

    #[Route('cart/decrease-{id}', name: 'app_cart_decrease')]
    public function decrease($id, Cart $cart)
    {
        $cart->decreaseNumberProductCart($id);
        $this->addFlash('success', 'Le produit à bien été supprimer à votre panier');
        return $this->redirectToRoute('app_cart');
    }
}
