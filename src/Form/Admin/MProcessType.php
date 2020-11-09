<?php

declare(strict_types=1);

namespace App\Form\Admin;

use App\Entity\MProcess;
use App\Form\AppTypeAbstract;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class MProcessType extends AppTypeAbstract
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $this->buildFormName($builder);
        $this->buildFormIsEnable($builder);
        $this->buildFormContent($builder);

        $this->buildFormContributors($builder);
        $this->buildFormDirValidators($builder);
        $this->buildFormPoleValidators($builder);

        $builder
            ->add('ref', TextType::class, [
                self::LABEL => 'Référence',
                self::REQUIRED => true,
                self::ATTR => [self::PLACEHOLDER => '_____',self::MAXLENGTH=>'5'],

            ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => MProcess::class,
        ]);
    }
}
