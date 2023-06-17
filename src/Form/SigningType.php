<?php

namespace App\Form;

use App\Entity\Signing;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class SigningType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('type')
            ->add('transfer_amount')
            ->add('season')
            ->add('player')
            ->add('left_club_instance')
            ->add('joined_club_instance')
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Signing::class,
        ]);
    }
}
