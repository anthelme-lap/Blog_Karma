<?php

namespace App\Controller;

use App\Entity\Reply;
use DateTimeImmutable;
use App\Entity\Article;
use App\Entity\Comment;
use App\Entity\Category;
use App\Form\CommentFormType;
use App\Repository\ArticleRepository;
use App\Repository\CommentRepository;
use App\Repository\CategoryRepository;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class HomeController extends AbstractController
{
    #[Route('/', name: 'app_home')]
    public function index(
    Request $request,
    ArticleRepository $articleRepository,
    CategoryRepository $categoryRepository,
    PaginatorInterface $paginator
    ): Response
    {
        $donnees = $articleRepository->findAll();

        // Pagination des articles
        $article = $paginator->paginate(
            $donnees, // On passe les données
            $request->query->getInt('page', 1), //Nuemro de la page en cours. 1 par defaut
            6 //Nombre d'article à afficher par page
        );
        return $this->render('home/index.html.twig',[
            'article' => $article,
            'categoryActive' => $categoryRepository->findBy(['active' => true]),
            'category' => $categoryRepository->findAll()
        ]);
    }

    #[Route('/article/detail/{slug}', name: 'app_detail', methods:['GET','POST'])]
    public function show(Article $article, 
        Request $request,
        CommentRepository $commentRepository,
        PaginatorInterface $paginator
    ): Response
    {
        
        $comment = new Comment();
        $user = $this->getUser();
        $form = $this->createForm(CommentFormType::class, $comment);
        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()){
            $comment->setUser($user);
            $comment->setArticle($article);
            $comment->setCreatedAt(new DateTimeImmutable());

            // On récupère l'identifiant du commentaire parent dans le formulaire
            $comment_parent_id = $form->get('commentParent')->getData();

            // 
            if ($comment_parent_id) {
                // On récupère le commentaire parent
                $comment_parent = $commentRepository->find($comment_parent_id);
                $comment->setCommentParent($comment_parent);
            }
            
            $commentRepository->add($comment, true);

        }

        if($form->isSubmitted()){
            $comment = new Comment();
            $form = $this->createForm(CommentFormType::class);
        }

        // Pagination des commentaires

        $donnees = $commentRepository->findAll();
        $comments = $paginator->paginate($donnees, $request->query->getInt('page', 1), 3);

        if($article){
            return $this->renderForm('home/show.html.twig',[
                'article' => $article,
                'form' => $form,
                'comments' => $comments,
             ]);
        }
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
