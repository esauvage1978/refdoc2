<?php

namespace App\Form\Backpack;

use App\Entity\Process;
use App\Entity\Backpack;
use App\Entity\Category;
use App\Entity\MProcess;
use App\Form\AppTypeAbstract;
use App\Security\CurrentUser;
use Doctrine\ORM\EntityRepository;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\DateTimeType;

class BackpackType extends AppTypeAbstract
{
    private $user;
    public function __construct(CurrentUser $currentUser)
    {
        $this->user = $currentUser->getUser();
    }

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $this->buildFormName($builder);
        $this->buildFormContent($builder,'Description', true);
        $builder
            ->add('category', EntityType::class, [
                self::CSS_CLASS => Category::class,
                self::CHOICE_LABEL => 'fullname',
                self::LABEL => 'Type de porte-document',
                self::MULTIPLE => false,
                self::REQUIRED => false,
                self::QUERY_BUILDER => function (EntityRepository $er) {
                    return $er->createQueryBuilder('c')
                        ->select('c')
                        ->setParameter('val', '1')
                        ->where('c.isEnable=:val')
                        ->orderBy('c.name', 'ASC');
                }
            ])
            ->add('dir1', TextType::class, [
                self::LABEL => 'Niveau 1',
                self::REQUIRED => false
            ])
            ->add('dir2', TextType::class, [
                self::LABEL => 'Niveau 2',
                self::REQUIRED => false
            ])
            ->add('dir3', TextType::class, [
                self::LABEL => 'Niveau 3',
                self::REQUIRED => false
            ])
            ->add('dir4', TextType::class, [
                self::LABEL => 'Niveau 4',
                self::REQUIRED => false
            ])
            ->add('dir5', TextType::class, [
                self::LABEL => 'Niveau 5',
                self::REQUIRED => false
            ])
            ->add('mProcess', EntityType::class, [
                self::CSS_CLASS => MProcess::class,
                self::CHOICE_LABEL => 'fullname',
                self::LABEL => 'Macro-processus',
                self::MULTIPLE => false,
                self::REQUIRED => false,

            ])
            ->add('process', EntityType::class, [
                self::CSS_CLASS => Process::class,
                self::CHOICE_LABEL => 'fullname',
                self::LABEL => 'Processus',
                self::MULTIPLE => false,
                self::REQUIRED => false,
            ])
            ->add(
                'updatedAt',
                DateTimeType::class,
                [
                    self::LABEL            => 'date de modification',
                    self::REQUIRED => false
                ]
            )
            ->add('ref', TextType::class, [
                self::LABEL => 'Référence',
                self::REQUIRED => false,
                self::ATTR => [self::PLACEHOLDER => '__-__-__', self::MAXLENGTH => 30, self::CSS_CLASS => 'text-lg bold'],
            ])            
            ->add(
                'isHelpInterService',
                CheckboxType::class,
                [
                    self::LABEL => 'Peut être utilisé dans le cadre de l\'aide inter-service',
                    self::REQUIRED => false,
                ]
            );
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Backpack::class,
        ]);
    }
}
