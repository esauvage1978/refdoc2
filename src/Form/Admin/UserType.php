<?php

declare(strict_types=1);

namespace App\Form\Admin;

use App\Entity\User;
use App\Form\AppTypeAbstract;
use App\Security\Role;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\TelType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class UserType extends AppTypeAbstract
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $this->buildFormName($builder);
        $this->buildFormIsEnable($builder);
        $this->buildFormContent($builder);

        $builder
            ->add('email', EmailType::class)
            ->add(
                'phone',
                TelType::class,
                [
                    self::LABEL => 'Téléphone',
                    self::REQUIRED => false,
                ]
            )
            ->add('roles', ChoiceType::class, [
                'choices' => Role::getDatas(),
                'multiple' => true,
                'expanded' => true,
                'mapped' => true,
                self::LABEL => 'form.roles',
            ])
            ->add(
                'iSsubscription',
                CheckboxType::class,
                [
                    self::LABEL => ' Autorise à recevoir hebdomadairement les actualités par rapport aux abonnements',
                    self::REQUIRED => false,
                ]
            )
            ->add(
                'isDoc',
                CheckboxType::class,
                [
                    self::LABEL => ' Service documentation',
                    self::REQUIRED => false,
                ]
            )
            ->add(
                'isControl',
                CheckboxType::class,
                [
                    self::LABEL => ' Service contrôle',
                    self::REQUIRED => false,
                ]
            )
            ->add(
                'emailValidated',
                CheckboxType::class,
                [
                    self::LABEL => ' ',
                    self::REQUIRED => false,
                ]
            )
            ->add('loginAt')
            ->add('createdAt')
            ->add('modifiedAt')
            ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => User::class,
        ]);
    }
}
