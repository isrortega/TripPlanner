<?php

namespace App\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;

class DriverType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('name', TextType::class, [
                'constraints' => [
                    new NotBlank(['message' => 'The name is required']),
                    new Length([
                        'max' => 50,
                        'maxMessage' => 'The name cannot be longer than {{ limit }} characters',
                    ]),
                ],
            ])
            ->add('surname', TextType::class, [
                'constraints' => [
                    new NotBlank(['message' => 'The surname is required']),
                    new Length([
                        'max' => 50,
                        'maxMessage' => 'The surname cannot be longer than {{ limit }} characters',
                    ]),
                ],
            ])
            ->add('license', ChoiceType::class, [
                'choices' => array_combine(range('A', 'Z'), range('A', 'Z')),
                'placeholder' => 'Please select a license',
                'constraints' => [
                    new NotBlank(['message' => 'License is required'])
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
