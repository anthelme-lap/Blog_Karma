<?php

namespace App\Controller;

use DateTimeImmutable;
use App\Entity\Article;
use App\Entity\Comment;
use App\Form\CommentFormType;
use App\Repository\ArticleRepository;
use App\Repository\CommentRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class CommentController extends AbstractController
{
    #[Route('/a', name: 'app_comment', methods:['GET','POST'])]
    public function show(Article $article,Request $request, ArticleRepository $articleRepository, CommentRepository $commentRepository): Response
    {
        $comment = new Comment();
        $user = $this->getUser();
        $form = $this->createForm(CommentFormType::class, $comment);
        $form->handleRequest($request);
        
        // dd($articleRepository->find(1));

        if(  $form->isSubmitted() && $form->isValid()){
            $comment->setCreatedAt(new DateTimeImmutable('now'));
            $comment->setUser($user);
            $commentRepository->persist($comment, true);
            $commentRepository->flush();
        }
        return $this->render('home/show.html.twig',[
            'form' => $form->createView()
        ]);
    }
}
