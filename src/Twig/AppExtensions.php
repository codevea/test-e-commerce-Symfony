<?php

namespace App\Twig;

use Twig\Extension\GlobalsInterface;
use Twig\Extension\AbstractExtension;
use App\Repository\CategoryRepository;

class AppExtensions extends AbstractExtension implements GlobalsInterface
{
  public function __construct(private CategoryRepository $categoryRepository)
  {
    $this->categoryRepository = $categoryRepository;
  }

  public function getGlobals(): array
  {
    return [
      'allCategory' => $this->categoryRepository->findAll(),
    ];
  }
}
