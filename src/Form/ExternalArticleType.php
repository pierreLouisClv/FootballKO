<?php

namespace App\Form;

use App\Entity\Club;
use App\Entity\ExternalArticle;
use App\Repository\ClubRepository;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\DateTimeType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ExternalArticleType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $championship = $options['attr']['champ'];

        $builder
            ->add('name', TextType::class)
            ->add('link', TextType::class)
            ->add("publication_date", DateType::class,[
                    'widget' => 'single_text',
                    'input' => 'datetime',
                    'data' => new \DateTime('today')]
            )
            ->add('info', TextType::class, [
                'data' => 'Voir article [à compléter]'
            ])
            ->add('club', EntityType::class, [
                'class' => Club::class,
                'choice_label' => 'clubName',
                'query_builder' => function (ClubRepository $repo) use ($championship){
                    return $repo->createQueryBuilder('c')
                        ->andWhere('c.championship = :championship')
                        ->setParameter(':championship', $championship)
                        ->orderBy('c.clubName', 'ASC');
                },
                'placeholder' => 'Article Championnat',
                'required' => false
            ])            ->add('submit',
                SubmitType::class);
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => ExternalArticle::class,
        ]);
    }
}
