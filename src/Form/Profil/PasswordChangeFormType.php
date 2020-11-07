<?php

declare(strict_types=1);

namespace App\Form\Profil;

use App\Form\AppTypeAbstract;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotBlank;

class PasswordChangeFormType extends AppTypeAbstract
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add(
                'plainPasswordOld',
                PasswordType::class,
                [self::LABEL => 'Mot de passe actuel']
            )
            ->add('plainPassword', PasswordType::class, [
                self::LABEL => 'Nouveau mot de passe',
                'constraints' => [
                    new NotBlank(['message' => 'Merci de saisir le mot de passe']),
                    new Length([
                        'min' => 8,
                        'minMessage' => 'Le mot de passe doit avoir au minimum {{ limit }} caractères.',
                        'max' => 4096,
                    ]),
                ],
            ])
            ->add(
                'plainPasswordConfirmation',
                PasswordType::class,
                [self::LABEL => 'Confirmation']
            );
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([]);
    }
}
