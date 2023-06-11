<?php

namespace App\Form;

use App\Entity\Championship;
use App\Entity\Club;
use App\Repository\ChampionshipRepository;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class AddClubType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('cityName')
            ->add('clubName')
            ->add('shortName')
            ->add('championship', EntityType::class, [
                'class' => Championship::class,
                'query_builder' => function (ChampionshipRepository $cr) {
                    return $cr->createQueryBuilder('c')
                        ->where('c.isActive = :isActive')
                        ->setParameter('isActive', true);
            }
            ])
            ->add('submit',
                SubmitType::class, [
                    'label' => 'Sauvegarder',
                ]
            );
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Club::class,
        ]);
    }
}
