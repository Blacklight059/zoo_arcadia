<?php

namespace App\Form;

use App\Entity\Animal;
use App\Entity\User;
use App\Entity\VetReport;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class VetReportType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('animalState')
            ->add('foodOffered')
            ->add('foodWeight')
            ->add('visitDate', null, [
                'widget' => 'single_text',
            ])
            ->add('stateDetails')
            ->add('animal', EntityType::class, [
                'class' => Animal::class,
                'choice_label' => 'firstname',
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => VetReport::class,
        ]);
    }
}
