<?php

namespace App\Form;

use App\Entity\Schools;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class SchoolFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('name', TextType::class, [
                'label' => 'schoolName',
                'translation_domain' => 'forms',
                'constraints' => [
                    new NotBlank([
                        'message' => 'Please enter a school name',
                    ]),
                    new Length([
                        'max' => 50,
                    ]),
                ],
                'attr' => [
                    'placeholder' => 'schoolName',
                ],
            ])
            ->add('city', TextType::class, [
                'label' => 'city',
                'translation_domain' => 'forms',
                'constraints' => [
                    new NotBlank([
                        'message' => 'Please enter a city',
                    ]),
                    new Length([
                        'max' => 100,
                    ]),
                ],
                'attr' => [
                    'placeholder' => 'city',
                ],
            ])
            ->add('zipCode', TextType::class, [
                'label' => 'zipCode',
                'translation_domain' => 'forms',
                'constraints' => [
                    new NotBlank([
                        'message' => 'Please enter a zip code',
                    ]),
                    new Length([
                        'max' => 10,
                    ]),
                ],
                'attr' => [
                    'placeholder' => 'zipCode',
                ],
            ])
            ->add('adress', TextType::class, [
                'label' => 'address',
                'translation_domain' => 'forms',
                'constraints' => [
                    new NotBlank([
                        'message' => 'Please enter an adress',
                    ]),
                    new Length([
                        'max' => 255,
                    ]),
                ],
                'attr' => [
                    'placeholder' => 'address',
                ],
            ])
            ->add('country', TextType::class, [
                'label' => 'country',
                'translation_domain' => 'forms',
                'constraints' => [
                    new NotBlank([
                        'message' => 'Please enter a country',
                    ]),
                    new Length([
                        'max' => 100,
                    ]),
                ],
                'attr' => [
                    'placeholder' => 'country',
                ],
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Schools::class,
        ]);
    }
}
