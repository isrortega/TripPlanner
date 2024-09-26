<?php

namespace App\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;

class VehicleType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('brand', TextType::class, [
                'constraints' => [
                    new NotBlank(['message' => 'Brand is required']),
                    new Length([
                        'max' => 50,
                        'maxMessage' => 'Brand cannot be longer than {{ limit }} characters'
                    ]),
                ],
            ])
            ->add('model', TextType::class, [
                'constraints' => [
                    new NotBlank(['message' => 'Model is required']),
                    new Length([
                        'max' => 50,
                        'maxMessage' => 'Model cannot be longer than {{ limit }} characters'
                    ]),
                ],
            ])
            ->add('plate', TextType::class, [
                'constraints' => [
                    new NotBlank(['message' => 'Plate is required']),
                    new Length([
                        'min' => 6,
                        'max' => 15,
                       'minMessage' => 'Plate must be at least {{ limit }} characters long',
                       'maxMessage' => 'Plate cannot be longer than {{ limit }} characters'
                    ]),
                ],
            ])
            ->add('licenseRequired', ChoiceType::class, [
                'choices' => array_combine(range('A', 'Z'), range('A', 'Z')),
                'placeholder' => 'Please select a required license',
                'constraints' => [
                    new NotBlank(['message' => 'Required license is required'])
                ],
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            // Configure your form options here
        ]);
    }
}
