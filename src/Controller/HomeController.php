<?php

namespace App\Controller;

use App\Entity\Article;
use App\Repository\ArticleRepository;
use App\Repository\CategoryRepository;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class HomeController extends AbstractController
{
    #[Route('/', name: 'app_home')]
    public function index(ArticleRepository $articleRepository,CategoryRepository $categoryRepository): Response
    {
        return $this->render('home/index.html.twig',[
            'article' => $articleRepository->findAll(),
            'category' => $categoryRepository->findBy(['active' => true])
        ]);
    }

    #[Route('/article/detail/{id}', name: 'app_detail', methods:['GET'])]
    public function show(Article $article): Response
    {
        if($article){
            return $this->render('home/show.html.twig',[
                'article' => $article
             ]);
        }
        return $this->redirectToRoute('app_home');
    }
}
