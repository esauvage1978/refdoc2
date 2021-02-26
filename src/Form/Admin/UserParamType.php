<?php

declare(strict_types=1);

namespace App\Form\Admin;

use App\Entity\UserParam;
use App\Form\AppTypeAbstract;
use App\Security\Role;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\TelType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class UserParamType extends AppTypeAbstract
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {

        $builder
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
            );
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => UserParam::class,
        ]);
    }
}
