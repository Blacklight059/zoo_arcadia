<?php

namespace App\Form;

use App\Entity\Habitat;
use App\Entity\HabitatComment;
use App\Entity\User;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class HabitatCommentType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('comment')
            ->add('commentDate', null, [
                'widget' => 'single_text',
            ])
            ->add('habitat', EntityType::class, [
                'class' => Habitat::class,
                'choice_label' => 'name',
            ])

        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => HabitatComment::class,
        ]);
    }
}
