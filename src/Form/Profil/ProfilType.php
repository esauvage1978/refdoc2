<?php

declare(strict_types=1);

namespace App\Form\Profil;

use App\Entity\User;
use App\Form\AppTypeAbstract;
use App\Form\Admin\UserParamType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TelType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;

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
            ->add('phone',TelType::class, [
                self::LABEL => 'Téléphone',
                self::REQUIRED => false,
            ])
        ->add('userParam', UserParamType::class)
            ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => User::class,
        ]);
    }
}
