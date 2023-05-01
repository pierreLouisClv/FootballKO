<?php

namespace App\Controller\Admin;

use App\Entity\Media;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\AssociationField;
use EasyCorp\Bundle\EasyAdminBundle\Field\ImageField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;
use SebastianBergmann\CodeCoverage\Report\Text;

class MediaCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return Media::class;
    }


    public function configureFields(string $pageName): iterable
    {
        $mediasdir = $this->getParameter('medias_directory');
        $uploadsDir = $this->getParameter('uploads_directory');

        yield TextField::new('name');

        $imageField = ImageField::new('filename', 'Média')
            ->setBasePath($uploadsDir)
            ->setUploadDir($mediasdir)
            ->setUploadedFileNamePattern('[slug]-[uuid].[extension]');

        if (Crud::PAGE_EDIT == $pageName){
            $imageField->setRequired(false);
        }

        yield $imageField;

        yield AssociationField::new('associatedClub')->setFormTypeOptions(
            ['label' => 'Club associé',
            'required' => false
            ]);

        yield AssociationField::new('associatedChampionship')->setFormTypeOptions(
            ['label' => 'Championnat associé',
                'required' => true
            ]);

    }

}
