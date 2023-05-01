<?php

namespace App\Controller\Admin;

use App\Entity\Championship;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;


class ChampionshipCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return Championship::class;
    }

    /*
    public function configureFields(string $pageName): iterable
    {
        return [
            IdField::new('id'),
            TextField::new('title'),
            TextEditorField::new('description'),
        ];
    }
    */
}
