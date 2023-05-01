<?php

namespace App\Controller\Admin;

use App\Entity\Club;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\AssociationField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;

class ClubCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return Club::class;
    }

   /* public function configureFields(string $pageName): iterable
    {
        yield TextField::new('cityName');
        yield TextField::new('clubName');
        yield AssociationField::new('championship');
    }*/

}
