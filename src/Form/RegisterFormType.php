<?php

namespace App\Form;

use App\Entity\User;
use Captcha\Bundle\CaptchaBundle\Form\Type\CaptchaType;
use Captcha\Bundle\CaptchaBundle\Validator\Constraints\ValidCaptcha;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\RepeatedType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Validator\Constraints\File;
use Symfony\Component\Validator\Constraints\Image;
use Symfony\Component\Validator\Constraints\NotBlank;
class RegisterFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('email', EmailType::class, [
                'required' => false,
                'constraints' => [
                    new NotBlank([
                        'message' => 'Email is required.',
                    ]),
                ],
            ])
            ->add('plainPassword', RepeatedType::class, [
                'type' => PasswordType::class,
                'required' => false,
                'first_options' => ['label' => 'Password'],
                'second_options' => ['label' => 'Repeat password'],
                'constraints' => [
                    new NotBlank([
                        'message' => 'Password is required.',
                    ]),
                ],
            ])
            ->add('firstName', TextType::class, [
                'required' => false,
                'constraints' => [
                    new NotBlank([
                        'message' => 'First name is required.',
                    ]),
                ],
            ])
            ->add('lastName', TextType::class, [
                'required' => false,
                'constraints' => [
                    new NotBlank([
                        'message' => 'Last name is required.',
                    ]),
                ],
            ])
            ->add('dateOfBirth', DateType::class, [
                'widget' => 'single_text',
                'required' => false,
                'constraints' => [
                    new NotBlank([
                        'message' => 'Date of birth is required.',
                    ]),
                ],
            ])
            ->add("captchaCode", CaptchaType::class, [
                'captchaConfig' => 'ExampleCaptchaUserRegistration',
                'required' => false,
                'constraints' => [
                    new ValidCaptcha([
                        'message' => 'Invalid captcha, please try again.',
                    ]),
                ],
            ])
            ->add('profilePicture', FileType::class, [
                'label' => 'Profile Picture',
                'mapped' => false,
                'required' => false,
            ])
            ->add('Register', SubmitType::class);
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => User::class,
        ]);
    }
}
