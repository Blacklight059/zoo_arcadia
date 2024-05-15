<?php

namespace App\Form;

use App\Entity\Animal;
use App\Entity\Habitat;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotBlank;

class AnimalType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('firstname', TextType::class, [
                'required' => true, 
                'label' => 'Titre',
                'constraints' => [
                    new NotBlank([
                    'message' => "Veuillez saisir un nom"
                    ]),
                    new Length([
                    'min' => 3,
                    'minMessage' => "Le titre doit contenir au minimum {{ limit }} caractères"
                    ]),
                ],
                'row_attr' => [
                    'class' => 'm-3',
                ],
            ])
            ->add('breed', TextType::class, [
                'required' => true, 
                'label' => 'Titre',
                'constraints' => [
                    new NotBlank([
                    'message' => "Veuillez saisir un nom"
                    ]),
                    new Length([
                    'min' => 3,
                    'minMessage' => "Le titre doit contenir au minimum {{ limit }} caractères"
                    ]),
                ],
                'row_attr' => [
                    'class' => 'm-3',
                ],
            ])
            ->add('habitat', EntityType::class, [
                'class' => Habitat::class,
                'choice_label' => 'name',
            ])
            ->add('images', FileType::class, [
                'label' => 'Images (PNG, JPEG, JPG)',
                'multiple' => true,
                'required' => false,
                'mapped' => false,
                'attr' => [
                    'accept' => 'image/png, image/jpeg, image/jpg',
                    'multiple' => 'multiple',
                ],
            ]);
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Animal::class,
        ]);
    }
}
