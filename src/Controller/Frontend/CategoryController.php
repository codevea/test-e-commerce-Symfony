<?php

namespace App\Controller\Frontend;

use App\Repository\CategoryRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

final class CategoryController extends AbstractController
{
    #[Route('/categorie-{slug}', name: 'app_category', schemes: ['https'])]
    public function category(CategoryRepository $categoryRepository, $slug): Response
    {
        $category = $categoryRepository->findOneBySlug($slug);

        if(!$category){
             return $this->redirectToRoute('app_home');
        };

        return $this->render('frontend/category.html.twig', [
            'category' => $category
        ]);
    }
}
