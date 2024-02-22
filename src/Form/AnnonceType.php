<?php

namespace App\Form;

use App\Entity\Annonce;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints as Assert;


class AnnonceType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('titre', null, [
                'constraints' => [
                    new Assert\NotBlank([
                        'message' => 'Please enter a title for your add',
                    ]),
                ],
            ])
            ->add('description', null, [
                'constraints' => [
                    new Assert\NotBlank([
                        'message' => 'Please provide a description for your item',
                    ]),
                ],
            ])
            ->add('categorie', ChoiceType::class, [
                'choices' => [
                    'Apparel and Fashion' => 'Apparel and Fashion',
                    'Books and Media' => 'Books and Media',
                    'Electronics' => 'Electronics',
                    'Health and Beauty' => 'Health and Beauty',
                    'Home and Kitchen' => 'Home and Kitchen',
                    'School Supplies' => 'School supplies',
                    'Sports and Outdoors' => 'Sports and Outdoors',
                    'Other' => 'other',
                ],
                'placeholder' => 'Choose a category',
                'constraints' => [
                    new Assert\NotBlank([
                        'message' => 'Please choose a category for your item',
                    ]),
                ],
            ])
            ->add('etat', ChoiceType::class, [
                'choices' => [
                    'New' => 'New',
                    'Like New' => 'Like New',
                    'Used' => 'Used',
                    'Damaged' => 'Damaged',
                    'Not Working' => 'Not Working',
                ],
                'placeholder' => 'Choose the condition',
                'constraints' => [
                    new Assert\NotBlank([
                        'message' => 'Please choose the condition of your item',
                    ]),
                ],
            ])
            ->add('localisation', null, [
                'constraints' => [
                    new Assert\NotBlank([
                        'message' => 'Please enter your location',
                    ]),
                ],
            ])
            ->add('images', FileType::class, [
                'label' => 'Images (JPG, PNG or GIF files)',
                'mapped' => false,
                'required' => false,
                'multiple' => true,
                'attr' => [
                    'accept' => 'image/*',
                    'multiple' => 'multiple', // Optional for better UX
                ],
            ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Annonce::class,
        ]);
    }
}
