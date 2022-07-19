<?php

namespace App\Controller\Admin;

use DateTimeImmutable;
use App\Entity\Category;
use Doctrine\ORM\EntityManagerInterface;
use EasyCorp\Bundle\EasyAdminBundle\Field\IdField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;
use EasyCorp\Bundle\EasyAdminBundle\Field\ImageField;
use EasyCorp\Bundle\EasyAdminBundle\Field\BooleanField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextEditorField;
use EasyCorp\Bundle\EasyAdminBundle\Field\AssociationField;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;

class CategoryCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return Category::class;
    }

    public function configureFields(string $pageName): iterable
    {
        return [
            IdField::new('id')->hideOnForm(),
            TextField::new('name')->setLabel('Nom catégorie'),
            BooleanField::new('active')->setLabel('Active'),
            // AssociationField::new('articles','Article'),
            TextEditorField::new('description')->setLabel('Description'),
            ImageField::new('image')
                    ->setBasePath('assetstyle/img')
                    ->setUploadDir('public/assetstyle/img')
                    ->setRequired(false),
        ];
    }

    public function persistEntity(EntityManagerInterface $entityManager, $entityInstance): void
    {
        if(!($entityInstance instanceof Category)) return;

        $entityInstance->setCreatedAt(new DateTimeImmutable('now'));

        parent::persistEntity($entityManager, $entityInstance);

    }

}
