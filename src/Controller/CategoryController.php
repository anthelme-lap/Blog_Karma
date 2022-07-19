<?php

namespace App\Controller;

use App\Entity\Category;
use App\Repository\CategoryRepository;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class CategoryController extends AbstractController
{
    #[Route('/category/article', name: 'app_category_article', methods:['GET'])]
    public function index(Category $category, CategoryRepository $categoryRepository): Response
    {
        $articles = $categoryRepository->findArticleByCategory($category);
        dd($articles);
        // if($category->getArticles()->getValues()){
            return $this->render('category/index.html.twig');
        // }
        
    }
}
