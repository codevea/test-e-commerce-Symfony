<?php

namespace App\Controller;

use App\Repository\ProductRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

final class ProductController extends AbstractController
{
    #[Route('/produit-{slug}', name: 'app_product')]
    public function index(ProductRepository $productRepository, $slug): Response
    {
        $product = $productRepository->findOneBySlug(['slug' => $slug]);

        if(!$product){
             return $this->redirectToRoute('app_home');
        };

        return $this->render('product/index.html.twig', [
            'product' => $product
        ]);
    }
}
