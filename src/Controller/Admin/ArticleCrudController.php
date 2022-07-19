<?php

namespace App\Controller\Admin;

use DateTimeImmutable;
use App\Entity\Article;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Security\Core\Security;
use EasyCorp\Bundle\EasyAdminBundle\Field\IdField;
use EasyCorp\Bundle\EasyAdminBundle\Field\SlugField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;
use EasyCorp\Bundle\EasyAdminBundle\Field\ImageField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextEditorField;
use EasyCorp\Bundle\EasyAdminBundle\Field\AssociationField;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;

class ArticleCrudController extends AbstractCrudController
{
    // public const = "assetstyle/img";
    // public const = "public/assetstyle/img";
    public function __construct(private Security $security)
    {
        $this->security = $security;
    }

    public static function getEntityFqcn(): string
    {
        return Article::class;
    }

    public function configureFields(string $pageName): iterable
    {
        return [
            IdField::new('id')->hideOnForm(),
            TextField::new('title','Titre'),
            SlugField::new('slug')->setTargetFieldName('title')->hideOnIndex(),
            TextField::new('tag')->hideOnIndex(),
            AssociationField::new('category', 'Catégorie'),
            TextEditorField::new('description','Description de l\'article'),
            ImageField::new('image','Image de l\'article')
                                    ->setBasePath('assetstyle/img')
                                    ->setUploadDir('public/assetstyle/img'),
            
        ];

    }

        public function persistEntity(EntityManagerInterface $entityManager, $entityInstance): void
        {
            if (!($entityInstance instanceof Article)) return;

            $entityInstance->setUser($this->security->getUser());
            $entityInstance->setCreatedAt(new DateTimeImmutable('now'));
            parent::persistEntity($entityManager, $entityInstance);
        }
}
