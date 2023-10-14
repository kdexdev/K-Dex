<?php

namespace App\Form;

use App\Entity\User;
use App\Validator\CustomUniqueEmail;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\RepeatedType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\Email;
use Symfony\Component\Validator\Constraints\IsTrue;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotBlank;

class RegistrationFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('email', EmailType::class, [
                'attr' => ['autocomplete' => 'email'],
                'required' => true,
                'constraints' => [
                    new NotBlank([
                        'message' => "Please enter an email address"
                    ]),
                    new Length([
                        'min' => 6,
                        'minMessage' => "Your email should consist of at least {{ limit }} characters.",
                        'max' => 180,
                        'maxMessage' => "Your email should consist of at most {{ limit }} characters."
                    ]),
                    new Email([
                        'message' => "Please enter a valid email address"
                    ]),
                    new CustomUniqueEmail([
                        'message' => "An account with this email already exists",
                    ])
                ]
            ])
            ->add('username', TextType::class, [
                'required' => true,
                'constraints' => [
                    new NotBlank([
                        'message' => 'Please enter a username'
                    ]),
                    new Length([
                        'min' => 6,
                        'minMessage' => 'Your username should consist of at least {{ limit }} characters',
                        'max' => 90,
                        'maxMessage' => 'Your username should consist of at most {{ limit }} characters'
                    ])
                ]
            ])
            ->add('passwords', RepeatedType::class, [
                'mapped' => false,
                'type' => PasswordType::class,
                'required' => true,
                'constraints' => [
                    new NotBlank([
                        'message' => 'Please enter a password'
                    ]),
                    new Length([
                        'min' => 6,
                        'minMessage' => 'Your password should consist of at least {{ limit }} characters',
                        'max' => 4096,
                        'maxMessage' => 'Your password should consist of at most {{ limit }} characters'
                    ])
                ],
                'invalid_message' => 'The passwords must match.',
            ])
            ->add('agreeTos', CheckboxType::class, [
                'required' => true,
                'mapped' => false,
                'constraints' => [
                    new IsTrue([
                        'message' => 'You are required to agree to our Terms of Service.'
                    ])
                ]
            ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => User::class,
            'csrf_protection' => true,
            'csrf_field_name' => '_token',
            'csrf_token_id'   => 'register_token'
        ]);
    }
}
