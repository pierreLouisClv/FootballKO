<?php

namespace App\Controller\Admin;

use App\Entity\Player;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\AssociationField;
use EasyCorp\Bundle\EasyAdminBundle\Field\ChoiceField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;

class PlayerCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return Player::class;
    }

    public function configureFields(string $pageName): iterable
    {
        yield TextField::new('firstName');
        yield TextField::new('lastName');
        yield ChoiceField::new('position', 'Position')
            ->setChoices([
                'Attaquant' => 'ATT',
                'Milieu Offensif' => 'MO',
                'Milieu Défensif' => 'MD',
                'Défenseur Latéral' => 'DL',
                'Défenseur Central' => 'DC',
                'Gardien' => 'GDB'
            ]);
        yield AssociationField::new('club');
    }

}
