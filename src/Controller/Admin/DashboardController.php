<?php

namespace App\Controller\Admin;

use App\Entity\About;
use App\Entity\Article;
use App\Entity\Category;
use Symfony\Component\HttpFoundation\Response;
use App\Controller\Admin\ArticleCrudController;
use App\Entity\Comment;
use Symfony\Component\Routing\Annotation\Route;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;
use EasyCorp\Bundle\EasyAdminBundle\Config\MenuItem;
use EasyCorp\Bundle\EasyAdminBundle\Config\Dashboard;
use EasyCorp\Bundle\EasyAdminBundle\Router\AdminUrlGenerator;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractDashboardController;

class DashboardController extends AbstractDashboardController
{
    #[Route('/admin', name: 'admin')]
    public function index(): Response
    {
        // return parent::index();

        // Option 1. You can make your dashboard redirect to some common page of your backend
        //
        $adminUrlGenerator = $this->container->get(AdminUrlGenerator::class);
        return $this->redirect($adminUrlGenerator->setController(ArticleCrudController::class)->generateUrl());

        // Option 2. You can make your dashboard redirect to different pages depending on the user
        //
        // if ('jane' === $this->getUser()->getUsername()) {
        //     return $this->redirect('...');
        // }

        // Option 3. You can render some custom template to display a proper dashboard with widgets, etc.
        // (tip: it's easier if your template extends from @EasyAdmin/page/content.html.twig)
        //
        // return $this->render('some/path/my-dashboard.html.twig');
    }

    public function configureDashboard(): Dashboard
    {
        return Dashboard::new()
            ->setTitle('Karma');
    }

    public function configureMenuItems(): iterable
    {
        yield MenuItem::section('Tableau de bord', 'fa fa-home');
        yield MenuItem::subMenu('Article', 'fas fa-list')->setSubItems([
            MenuItem::linkToCrud('Ajouter', 'fas fa-plus', Article::class)->setAction(Crud::PAGE_NEW),
            MenuItem::linkToCrud('Voir les articles', 'fas fa-eye', Article::class)->setAction(Crud::PAGE_INDEX)
        ]);
        yield MenuItem::subMenu('Catégorie', 'fas fa-list')->setSubItems([
            MenuItem::linkToCrud('Ajouter', 'fas fa-plus', Category::class)->setAction(Crud::PAGE_NEW),
            MenuItem::linkToCrud('Voir les articles', 'fas fa-eye', Category::class)->setAction(Crud::PAGE_INDEX)
        ]);
        yield MenuItem::subMenu('A propos', 'fas fa-list')->setSubItems([
            MenuItem::linkToCrud('Ajouter', 'fas fa-plus', About::class)->setAction(Crud::PAGE_NEW),
            MenuItem::linkToCrud('Voir les articles', 'fas fa-eye', About::class)->setAction(Crud::PAGE_INDEX)
        ]);
        yield MenuItem::subMenu('Commentaire', 'fa fa-comments')->setSubItems([
            MenuItem::linkToCrud('Voir les articles', 'fas fa-eye', Comment::class)->setAction(Crud::PAGE_INDEX)
        ]);
    }
}
