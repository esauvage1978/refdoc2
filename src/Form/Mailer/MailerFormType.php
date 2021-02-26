<?php

namespace App\Form\Mailer;

use App\Entity\User;
use App\Form\AppTypeAbstract;
use App\Repository\ActionRepository;
use App\Repository\CorbeilleRepository;
use App\Repository\DeployementRepository;
use App\Repository\OrganismeRepository;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityRepository;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;

class MailerFormType extends AppTypeAbstract
{
    public function buildFormSubjectContent(FormBuilderInterface $builder): FormBuilderInterface
    {
        return $builder
            ->add('subject', TextType::class, [
                self::LABEL => ' Objet',
                self::ATTR => ['placeholder' => 'Objet du mail : '],
                self::REQUIRED => true
            ])
            ->add('content', TextareaType::class, [
                self::LABEL => ' Contenu',
                self::REQUIRED => true,
                self::ATTR => [self::ROWS => 8, self::CSS_CLASS => 'textarea'],
            ]);
    }
}
