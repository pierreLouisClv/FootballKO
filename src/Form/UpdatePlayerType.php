<?php

namespace App\Form;

use App\Entity\Article;
use App\Entity\Championship;
use App\Entity\Player;
use App\Repository\ArticleRepository;
use App\Repository\ChampionshipRepository;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\DateIntervalType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class UpdatePlayerType extends AbstractType
{

    public function __construct(public ChampionshipRepository $championshipRepository)
    {
    }

    public function buildForm(FormBuilderInterface $builder, array $options): void
    {


        $valMin = $options['attr']['valMin'];
        $valMax = $options['attr']['valMax'];
        $club = $options['attr']['club'];



        if($options['attr']['create']){
            $builder
                ->add('first_name')
                ->add('last_name')
                ->add('position', ChoiceType::class, [
                    'choices' => [
                        'Non renseigné' => null,
                        'Gardien de but' => 'GDB',
                        'Défenseur central' => 'DC',
                        'Défenseur latéral' => 'DL',
                        'Milieu défensif' => 'MD',
                        'Milieu offensif' => 'MO',
                        'Attaquant' => 'A'
                    ]
                ]);
        }
        $builder
            ->add('injury_status', ChoiceType::class, [
                'required' => false,
        'choices' => [
            'Disponible' => "OK",
            'Incertain' => "uncertain",
            'Blessé' => "injured",
            'Malade' => "sick",
            'Suspendu' => "suspended",
            "Ecarté" => 'exclude',
            "Hors-Groupe" => 'out_of_group'
        ],
    ])
            ->add('day_return', ChoiceType::class, [
                'choices' => $this->getDateReturnChoices($valMin, $valMax),
                'required' => false,
                'placeholder' => '',
            ])

            ->add('date_of_return_is_exact', CheckboxType::class, [
                'required' => false,
            ])

            ->add('info', EntityType::class, [
                'class' => Article::class,
                'choice_label' => function (Article $article) {
                    return sprintf('%s - %s', $article->getPublishedAt()->format('d/m/y'), $article->getTitle());
                },
                'query_builder' => function (ArticleRepository $articleRepository) use ($club) {
                    return $articleRepository->createQueryBuilder('article')
                        ->andWhere('article.mentionned_club = :club')
                        ->setParameter('club', $club)
                        ->orderBy('article.publishedAt', 'DESC');
                },
                'placeholder' => '',
                'required' => false
            ])

            ->add('injury_type', TextType::class, [
        'required' => false
])
            ->add('submit',
                SubmitType::class, [
                    'label' => 'Sauvegarder',
                    'attr' => [
                        'class' => 'btn btn-success'
                    ]

                ]
            );

    }

    private function getDateReturnChoices(int $valMin, int $valMax): array
    {
        $choices = ['Fin de saison' => 0];

        for ($i = $valMin; $i <= $valMax; $i++) {
            $choices[$i] = $i;
        }

        return $choices;
    }
    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Player::class,
        ]);
    }

}
