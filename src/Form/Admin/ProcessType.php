<?php

declare(strict_types=1);

namespace App\Form\Admin;

use App\Entity\Process;
use App\Form\AppTypeAbstract;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ProcessType extends AppTypeAbstract
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $this->buildFormName($builder);
        $this->buildFormMProcess($builder);
        $this->buildFormContent($builder);
        $this->buildFormIsEnable($builder);
        $this->buildFormValidators($builder);
        $this->buildFormContributors($builder);

        $builder
            ->add('ref', TextType::class, [
                self::LABEL => 'Référence',
                self::REQUIRED => true,
                self::ATTR => [self::PLACEHOLDER => '000'],
            ])
            ->add('grouping', TextType::class, [
                self::LABEL => 'Groupement',
                self::REQUIRED => false,
            ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Process::class,
        ]);
    }
}
