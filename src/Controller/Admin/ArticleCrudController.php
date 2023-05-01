<?php

namespace App\Controller\Admin;

use App\Entity\Article;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\AssociationField;
use EasyCorp\Bundle\EasyAdminBundle\Field\DateField;
use EasyCorp\Bundle\EasyAdminBundle\Field\DateTimeField;
use EasyCorp\Bundle\EasyAdminBundle\Field\SlugField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextEditorField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;

class ArticleCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return Article::class;
    }


    /*public function configureFields(string $pageName): iterable
    {
        yield TextField::new('title')->setFormTypeOptions([
            'label' => 'Titre']);;
        yield TextEditorField::new('content')->setFormTypeOptions([
            'label' => 'Contenu de l\'article']);
        yield AssociationField::new('category')->setFormTypeOptions([
            'label' => 'Catégorie']);
        yield DateTimeField::new('publishedAt')->setFormTypeOptions([
            'html5' => false,
            'years' => range(date('Y'), date('Y')),
            'data' => new \DateTime('tomorrow 9:00')])->setFormTypeOptions(
                ['label' => 'Date de publication']);
        yield AssociationField::new('media');

    }*/

}
