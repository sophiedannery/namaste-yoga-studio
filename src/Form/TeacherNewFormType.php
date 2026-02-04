<?php

namespace App\Form;

use App\Entity\User;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\Constraints\Regex;

class TeacherNewFormType extends AbstractType
{
    // =========================
    // Formulaire de création d’un professeur (admin)
    // =========================
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            // Champ prénom du professeur
            ->add('firstName', TextType::class, [
                'attr' => [
                    'class' => 'form-control mb-3',
                ],
            ])
            // Champ nom du professeur
            ->add('lastName', TextType::class, [
                'attr' => [
                    'class' => 'form-control mb-3',
                ],
            ])
            // Champ email du professeur
            ->add('email', EmailType::class, [
                'attr' => [
                    'class' => 'form-control mb-3',
                ],
            ])
            // Champ mot de passe
            ->add('plainPassword', PasswordType::class, [
                'mapped' => false,
                'attr' => [
                    'autocomplete' => 'new-password',
                    'class' => 'form-control mb-3'
                ],
                'constraints' => [
                    new NotBlank([
                        'message' => 'Veuillez renseigner un mot de passe.',
                    ]),
                    new Length([
                        'min' => 9,
                        'minMessage' => 'Le mot de passe doit avoir au moins 9 caractères.',
                        'max' => 4096,
                    ]),
                    new Regex([
                        'pattern' => '/^(?=.*[A-Z])(?=.*[a-z])(?=.*\d).+$/',
                        'message' => 'Le mot de passe doit contenir au moins une majuscule, une minuscule et un chiffre.'
                    ])
                ],
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => User::class,
        ]);
    }
}
