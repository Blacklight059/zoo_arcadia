<?php

namespace App\Form;

use App\Entity\Animal;
use App\Entity\FoodConsumption;
use App\Entity\User;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class FoodConsumption1Type extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('dateTime', null, [
                'widget' => 'single_text',
            ])
            ->add('foodType')
            ->add('quantity')
            ->add('user', EntityType::class, [
                'class' => User::class,
                'choice_label' => 'name',
            ])
            ->add('animal', EntityType::class, [
                'class' => Animal::class,
                'choice_label' => 'firstname',
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => FoodConsumption::class,
        ]);
    }
}
