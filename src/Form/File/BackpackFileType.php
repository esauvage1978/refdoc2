<?php

namespace App\Form\File;

use App\Entity\BackpackFile;
use App\Form\AppTypeAbstract;
use Symfony\Component\Form\Extension\Core\Type\DateTimeType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class BackpackFileType extends AppTypeAbstract
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('file', FileType::class,
                [
                    self::LABEL => 'Choisir le fichier',
                    self::REQUIRED => false,
                    self::ATTR => [self::CSS_CLASS => 'custom-file-input'],
                ])
            ->add('title', TextType::class,
                [
                    self::LABEL => 'titre',
                    self::REQUIRED => false
                ])
            ->add('content', TextareaType::class,
                [
                    'label' => 'Description',
                    'required' => false,
                    self::ATTR => [self::ROWS => 3, self::CSS_CLASS => 'textarea'],
                ])
            ->add('modifyAt', DateTimeType::class,
                [
                    'label' => ' ',
                    'required' => false
                ]);
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => BackpackFile::class,
        ]);
    }
}
