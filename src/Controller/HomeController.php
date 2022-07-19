<?php

namespace App\Controller;

use DateTimeImmutable;
use App\Entity\Article;
use App\Entity\Comment;
use App\Entity\Category;
use App\Form\CommentFormType;
use App\Repository\ArticleRepository;
use App\Repository\CommentRepository;
use App\Repository\CategoryRepository;
use Symfony\Component\HttpFoundation\Request;
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
            'categoryActive' => $categoryRepository->findBy(['active' => true]),
            'category' => $categoryRepository->findAll()
        ]);
    }

    #[Route('/article/detail/{id}', name: 'app_detail', methods:['GET','POST'])]
    public function show(Article $article, 
        Request $request,
        ArticleRepository $articleRepository, 
        CommentRepository $commentRepository
    ): Response
    {
        $comment = new Comment();
        $user = $this->getUser();
        $form = $this->createForm(CommentFormType::class, $comment);
        $form->handleRequest($request);

        if ($user)
        {
            if($form->isSubmitted() && $form->isValid())
            {
                $comment->setCreatedAt(new DateTimeImmutable('now'));
                $comment->setUser($user);
                $comment->setArticle($article);
                $commentRepository->add($comment, true);

                $this->addFlash('danger','Veuillez vous connecter avant de laisser un commentaire');
                
            }
        }
        else{
            $this->addFlash('danger','Veuillez vous connecter avant de laisser un commentaire');
        }

        // On reinitialise le champ apres soumission
        if($form->isSubmitted()){ 
            $comment = new Comment();
            $form = $this->createForm(CommentFormType::class, $comment);
        }
       
        
        if($article){
            return $this->render('home/show.html.twig',[
                'article' => $article,
                'form' => $form->createView()
             ]);
        }
    }

    #[Route('/comment', name:'app_comment', methods:['GET'])]
    public function displayComment(CommentRepository $commentRepository): Response
    {
       return $this->render('home/show.html.twig',[
          'comments' => $commentRepository->findAll()
        ]); 
    
    
    }

    #[Route('/category/{id}', name: 'app_category', methods:['GET'])]
    public function category(Category $category, CategoryRepository $categoryRepository): Response
    {
        $article = $category->getArticles()->getValues();
        // dd($art);
        if (!$article) {
            return $this->redirectToRoute('app_home');
        }

        if($category){
            return $this->render('home/category.html.twig',[
                'articles' => $article,
                'category' => $categoryRepository->findAll()
            ]);
        }
    }
    
}
