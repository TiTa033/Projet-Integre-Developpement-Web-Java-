<?php

namespace App\Form;


use App\Entity\Book;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;


class EditBookType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
{
    $builder
        ->add('ref')
        ->add('title')
        ->add('published', CheckboxType::class, [
            'label' => 'Published',
            'required' => false,
        ])
        ->add('publicationDate')
        ->add('Category', ChoiceType::class, [
            'choices' => [
                'Mystery' => 'mystery',
                'Sci-fi' => 'sci-fi',
                'History' => 'history',
                'Novel' => 'novel', 
            ],
            'placeholder' => 'Choose a category',
        ])
        ->add('author')
        ->add('submit',SubmitType::class)
    ;
}

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Book::class,
        ]);
    }
}
