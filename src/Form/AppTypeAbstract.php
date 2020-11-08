<?php

declare(strict_types=1);

namespace App\Form;

use App\Entity\MProcess;
use App\Entity\Organisme;
use App\Entity\User;
use Doctrine\ORM\EntityRepository;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;

abstract class AppTypeAbstract extends AbstractType
{
    public const LABEL = 'label';
    public const DATA = 'data';
    public const REQUIRED = 'required';
    public const ROW_ATTR = 'row_attr';
    public const ATTR = 'attr';
    public const CHOICE_LABEL = 'choice_label';
    public const MULTIPLE = 'multiple';
    public const CSS_CLASS = 'class';
    public const ROWS = 'rows';
    public const GROUP_BY = 'group_by';
    public const QUERY_BUILDER = 'query_builder';
    public const DISABLED = 'disabled';
    public const MAXLENGTH = 'maxlength';
    public const PLACEHOLDER = 'placeholder';

    public function buildFormName(FormBuilderInterface $builder,$label='Nom'): void
    {
        $builder
            ->add('name', TextType::class, [
                self::LABEL => $label,
                self::REQUIRED => true,
                self::ATTR => [self::MAXLENGTH => 255],
            ]);
    }

    public function buildFormIsEnable(FormBuilderInterface $builder, $label=' '): void
    {
        $builder
            ->add(
                'isEnable',
                CheckboxType::class,
                [
                    self::LABEL => $label,
                    self::REQUIRED => false,
                ]
            );
    }

    public function buildFormContent(FormBuilderInterface $builder, $label= 'Description'): void
    {
        $builder
            ->add('content', TextareaType::class, [
                self::LABEL => $label,
                self::REQUIRED => false,
                self::ATTR => [self::ROWS => 3, self::CSS_CLASS => 'textarea'],
            ]);
    }

}
