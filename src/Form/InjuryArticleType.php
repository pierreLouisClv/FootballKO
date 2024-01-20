<?php

namespace App\Form;

use App\Entity\Championship;
use App\Entity\InjuryArticle;
use App\Entity\Media;
use App\Repository\MediaRepository;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;
use FOS\CKEditorBundle\Form\Type\CKEditorType;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\DateTimeType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class InjuryArticleType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $championship = $options['attr']['champ'];

        $builder
            ->add('title', TextType::class)
            ->add('content', CKEditorType::class)
            ->add('introduction', TextareaType::class)
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
                        ->andWhere('m.isArchived = :boolean')
                        ->setParameter(':boolean', false)
                        ->orderBy('m.name', 'ASC');
                },
                'placeholder' => '-- Sélectionner un média --'
            ])
            ->add("published_at", DateTimeType::class,[
                'widget' => 'single_text',
                'input' => 'datetime',
                'data' => new \DateTime('tomorrow 9:00')]
            )
            ->add('submit',
                SubmitType::class);

    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => InjuryArticle::class,
        ]);
    }
}
