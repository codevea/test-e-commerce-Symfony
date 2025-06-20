<?php

namespace App\Controller\Account;

use App\Repository\ProductRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

final class WishlistController extends AbstractController
{
    #[Route('/profil/liste-des-favoris', name: 'app_wishlist')]
    public function wishlist(): Response
    {
        return $this->render('account/wishlist/wishlist.html.twig');
    }

    // Ajoute le produit à la liste des favoris
    #[Route('/profil/ajouter-a-ma-liste-des-favoris-{id}', name: 'app_wishlist_add')]
    public function wishlistAdd($id, ProductRepository $productRepository, EntityManagerInterface $entityManagerInterface, Request $request): Response
    {
        $product = $productRepository->findOneById($id);

        if ($product) {
            $this->getUser()->addWishlist($product);
            $entityManagerInterface->flush();
            $this->addFlash('success', 'Le produit à été ajouter à votre liste des favoris');
        }

        return $this->redirect($request->headers->get('referer')); // Récupère l'URL de la dernière page visitée.
    }

    // Supprime le produit de la liste des favoris
    #[Route('/profil/supprime-le-favoris-{id}', name: 'app_wishlist_remove')]
    public function wishlistRemove($id, ProductRepository $productRepository, EntityManagerInterface $entityManagerInterface, Request $request): Response
    {
        $product = $productRepository->findOneById($id);

        if ($product) {
            $this->getUser()->removeWishlist($product);
            $entityManagerInterface->flush();
            $this->addFlash('warning', 'Le produit a été supprimé de votre liste de favoris.');
        } 
        return $this->redirect($request->headers->get('referer')); // Récupère l'URL de la dernière page visitée.
    }
}
