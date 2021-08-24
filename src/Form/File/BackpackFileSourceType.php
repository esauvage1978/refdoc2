<?php

namespace App\Form\File;

use App\Entity\BackpackFileSource;
use App\Form\AppTypeAbstract;
use Symfony\UX\Dropzone\Form\DropzoneType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\DateTimeType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;

class BackpackFileSourceType extends AppTypeAbstract
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('file', DropzoneType::class,
                [
                    self::LABEL => 'Choisir le fichier',
                    self::REQUIRED => false,
                ])
            ->add('title', TextType::class,
                [
                    self::LABEL => 'titre',
                    self::REQUIRED => true
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
            'data_class' => BackpackFileSource::class,
        ]);
    }
}
