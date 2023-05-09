<?php

namespace App\Form;

use App\Entity\Player;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\DateIntervalType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class AddPlayerType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('first_name')
            ->add('last_name')
            ->add('position', ChoiceType::class, [
                'choices' => [
                    'Gardien de but' => 'GDB',
                    'Défenseur central' => 'DC',
                    'Défenseur latéral' => 'DL',
                    'Milieu défensif' => 'MD',
                    'Milieu offensif' => 'MO',
                    'Attaquant' => 'A'
                ],
                'required' => false
                ])
            ->add('injury_status', ChoiceType::class, [
                'required' => false,
                'choices' => [
                    'Disponible' => "OK",
                    'Incertain' => "uncertain",
                    'Blessé' => "injured",
                    'Suspendu' => "suspended",
                    "Ecarté" => 'exclude',
                    "Hors-Groupe" => 'out_of_group'
                ],
            ])
            ->add('downtime', DateIntervalType::class, [
                'required' => false,

                'widget'      => 'integer', // render a text field for each part
                // 'input'    => 'string',  // if you want the field to return a ISO 8601 string back to you

                // customize which text boxes are shown
                'with_years'  => false,
                'with_months' => true,
                'with_days'   => true,
                'with_hours'  => false,
                'attr' => ['class' => 'form-control-inline']
            ])
            ->add('progress', TextType::class, [
                'required' => false
            ])
            ->add('injury_type', TextType::class, [
                'required' => false
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
            'data_class' => Player::class,
        ]);
    }
}
