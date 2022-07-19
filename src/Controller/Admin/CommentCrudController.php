<?php

namespace App\Controller\Admin;

use DateTimeImmutable;
use App\Entity\Comment;
use Doctrine\ORM\EntityManagerInterface;
use EasyCorp\Bundle\EasyAdminBundle\Field\IdField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextEditorField;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\AssociationField;

class CommentCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return Comment::class;
    }

    
    public function configureFields(string $pageName): iterable
    {
        return [
            IdField::new('id')->hideOnForm(),
            AssociationField::new('user','Utilisateur'),
            AssociationField::new('article','Article'),
            TextEditorField::new('message','Commentaire'),
        ];
    }
    

    // public function persistEntity(EntityManagerInterface $entityManager, $entityInstance): void
    // {
    //     if (!($entityInstance instanceof Comment)) return;

    //     $entityInstance->setCreatedAt(new DateTimeImmutable('now'));
    // }
}
