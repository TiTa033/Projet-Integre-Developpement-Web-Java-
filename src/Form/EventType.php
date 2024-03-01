<?php

namespace App\Form;

use App\Entity\Event;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\DateTimeType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Vich\UploaderBundle\Form\Type\VichFileType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;

class EventType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('name', TextType::class, [
                'label' => 'Name',
                'required' => false,
            ])
            ->add('description', TextareaType::class, [
                'label' => 'Description',
                'required' => false,
            ])

            ->add('maxAttendees', IntegerType::class, [
                'label' => 'Maximum number of attendees',
                'required' => false,
            ])

            ->add('startDate', DateTimeType::class, [
                'label' => 'Start Date and Time',
                'required' => false,
                'widget' => 'single_text',
                'html5' => true,
                'attr' => [
                    'class' => 'datetimepicker', // You can add a custom class if needed
                ],
            ])
            ->add('endDate', DateTimeType::class, [
                'label' => 'End Date and Time',
                'required' => false,
                'widget' => 'single_text',
                'html5' => true,
                'attr' => [
                    'class' => 'datetimepicker', // You can add a custom class if needed
                ],
            ])
            ->add('location', TextType::class, [
                'label' => 'Location',
                'required' => false,
                'attr' => array(
                    'readonly' => true,
                ),
               
            ])
            ->add('images', FileType::class, [
                'label' => 'Images',
                'required' => false,
                'multiple' => true,
                'mapped' => false,
                'attr' => ['accept' => 'image/*'],
            ])
            
           ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Event::class,
        ]);
    }



   
}
