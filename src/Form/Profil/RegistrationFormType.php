<?php

declare(strict_types=1);

namespace App\Form\Profil;

use App\Entity\User;
use App\Form\AppTypeAbstract;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotBlank;

class RegistrationFormType extends AppTypeAbstract
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('name', TextType::class, [
                self::LABEL => 'Nom',
                self::REQUIRED => true,
            ])
            ->add(
                'email',
                EmailType::class,
                [
                    self::LABEL => 'Email',
                    self::REQUIRED => true,
                ]
            )
            ->add('plainPassword', PasswordType::class, [
                self::LABEL => 'Mot de passe',
                'constraints' => [
                    new NotBlank(['message' => 'Merci de saisir le mot de passe']),
                    new Length([
                        'min' => 8,
                        'minMessage' => 'Le mot de passe doit avoir au minimum {{ limit }} caractères.',
                        'max' => 255,
                    ]),
                ],
            ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => User::class,
        ]);
    }
}
