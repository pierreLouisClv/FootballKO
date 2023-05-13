<?php

namespace App\Form;

use App\Entity\Article;
use App\Entity\Category;
use App\Entity\Club;
use App\Entity\Media;
use App\Repository\CategoryRepository;
use App\Repository\ClubRepository;
use App\Repository\MediaRepository;
use FOS\CKEditorBundle\Form\Type\CKEditorType;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\DateTimeType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ArticleType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {

        $championship = $options['attr']['champ'];

        $builder
            ->add('title', TextType::class)
            ->add('content', CKEditorType::class)
            ->add('introduction', TextareaType::class)
            ->add('category', EntityType::class, [
                'class' => Category::class,
                'choice_label' => 'name',
                'query_builder' => function (CategoryRepository $repo) {
                    return $repo->createQueryBuilder('c')
                        ->orderBy('c.name', 'ASC');
                },
                'placeholder' => '-- Sélectionner une catégorie --'
            ])
            ->add('mentionned_club', EntityType::class, [
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
            ])
            ->add("media", EntityType::class, [
                'class' => Media::class,
                'choice_label' => function (Media $media) use ($championship) {
                    $label = $championship->getChampName() . ' - ';
                    if ($media->getAssociatedClub() !== null) {
                        $label .= $media->getAssociatedClub()->getClubName() . ' - ';
                    }
                    $label .= $media->getName();
                    return trim($label);
                },
                'query_builder' => function (MediaRepository $repo) use ($championship){
                    return $repo->createQueryBuilder('m')
                        ->andWhere('m.associatedChampionship = :championship')
                        ->setParameter(':championship', $championship)
                        ->orderBy('m.name', 'ASC');
                },
                'placeholder' => '-- Sélectionner un média --'
            ])
            ->add("published_at", DateTimeType::class,[
                    'widget' => 'single_text',
                    'input' => 'datetime',
                    'data' => new \DateTime('tomorrow 9:00')]
            )
            ->add('keywords', TextareaType::class)
            ->add('submit',
                SubmitType::class);

    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Article::class,
        ]);
    }
}
