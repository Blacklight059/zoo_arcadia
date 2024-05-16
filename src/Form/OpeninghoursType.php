<?php

namespace App\Form;

use App\Entity\Openinghours;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class OpeninghoursType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('day_of_week')
            ->add('openning_time', null, [
                'widget' => 'single_text',
            ])
            ->add('closing_time', null, [
                'widget' => 'single_text',
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Openinghours::class,
        ]);
    }
}
