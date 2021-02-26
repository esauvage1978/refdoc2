<?php

declare(strict_types=1);

namespace App\Form\Admin;

use App\Entity\Category;
use App\Form\AppTypeAbstract;
use App\Workflow\WorkflowData;
use Symfony\Component\Form\Extension\Core\Type\ColorType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\RangeType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class CategoryType extends AppTypeAbstract
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $this->buildFormName($builder, 'Titre du type de document');
        $this->buildFormIsEnable($builder, ' Rendre ce type de document utilisable');
        $this->buildFormContent($builder);

        $builder
            ->add(
                'isValidatedByADD',
                CheckboxType::class,
                [
                    self::LABEL => "Validation par un agent de Direction",
                    self::REQUIRED => false,
                ]
            )
            ->add('ref', TextType::class, [
                self::LABEL => 'Référence',
                self::REQUIRED => true,
                self::ATTR => [self::PLACEHOLDER => '___', self::MAXLENGTH => 3],
            ])
            ->add(
                'isValidatedByControl',
                CheckboxType::class,
                [
                    self::LABEL => "Passage par le service contrôle",
                    self::REQUIRED => false,
                ]
            )
            ->add(
                'timeBeforeRevision',
                RangeType::class,
                [
                    self::LABEL => "Délai avant révision",
                    'data' => 12,
                    self::ATTR => [
                        'min' => 1,
                        'max' => 24,
                        self::CSS_CLASS => 'range'
                    ]
                ]
            )
            ->add(
                'isValidatedByDoc',
                CheckboxType::class,
                [
                    self::LABEL => "Passage par le service documentation",
                    self::REQUIRED => false,
                ]
            )
            ->add(
                'icone',
                TextType::class,
                [
                    self::LABEL => "Icône",
                    self::REQUIRED => false,
                ]
            )
            ->add(
                'bgColor',
                ColorType::class,
                [
                    self::LABEL => "Couleur du fond",
                    self::REQUIRED => false,
                ]
            )
            ->add(
                'foreColor',
                ColorType::class,
                [
                    self::LABEL => "Couleur du texte",
                    self::REQUIRED => false,
                ]
            );
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Category::class,
        ]);
    }
}
