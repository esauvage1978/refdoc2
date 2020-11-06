<?php

declare(strict_types=1);

namespace App\Form\Profil;

use App\Entity\User;
use App\Form\AppTypeAbstract;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ProfilType extends AppTypeAbstract
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $this->buildFormName($builder);
        $this->buildFormContent($builder);

        $builder
            ->add('email', EmailType::class, [
                self::LABEL => 'Mail',
                self::REQUIRED => true,
            ])
            ->add('phone', TextType::class, [
                self::LABEL => 'Téléphone',
                self::REQUIRED => false,
            ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => User::class,
        ]);
    }
}
