<?php

namespace App\Form\Mailer;

use App\Entity\User;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityRepository;
use App\Repository\BackpackRepository;
use App\Repository\MProcessRepository;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;

class MailerFormBackpackType extends MailerFormType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $this->buildFormSubjectContent($builder);

        $builder

            ->add('admp', EntityType::class, [
                self::CSS_CLASS => User::class,
                self::CHOICE_LABEL => 'name',
                self::LABEL => 'Agent de Direction du macro processus',
                self::MULTIPLE => true,
                self::ATTR => [self::CSS_CLASS => 'select2'],
                self::REQUIRED => false,
                self::QUERY_BUILDER => function (EntityRepository $er) use ($options) {
                    return $er->createQueryBuilder(UserRepository::ALIAS)
                        ->select(
                            UserRepository::ALIAS,
                            MProcessRepository::ALIAS,
                            BackpackRepository::ALIAS
                        )
                        ->leftJoin(UserRepository::ALIAS . '.mProcessDirValidators', MProcessRepository::ALIAS)
                        ->leftJoin(MProcessRepository::ALIAS . '.backpacks', BackpackRepository::ALIAS)
                        ->where(BackpackRepository::ALIAS . '.id = :actionid')
                        ->setParameter('actionid', $options['data']['data'])
                        ->orderBy(UserRepository::ALIAS . '.name', 'ASC');
                },
            ])

            ->add('pilotemp', EntityType::class, [
                self::CSS_CLASS => User::class,
                self::CHOICE_LABEL => 'name',
                self::LABEL => ' Pilote du macro processus',
                self::MULTIPLE => true,
                self::ATTR => [self::CSS_CLASS => 'select2'],
                self::REQUIRED => false,
                self::QUERY_BUILDER => function (EntityRepository $er) use ($options) {
                    return $er->createQueryBuilder(UserRepository::ALIAS)
                        ->select(
                            UserRepository::ALIAS,
                            MProcessRepository::ALIAS,
                            BackpackRepository::ALIAS
                        )
                        ->leftJoin(UserRepository::ALIAS . '.mProcessPoleValidators', MProcessRepository::ALIAS)
                        ->leftJoin(MProcessRepository::ALIAS . '.backpacks', BackpackRepository::ALIAS)
                        ->where(BackpackRepository::ALIAS . '.id = :actionid')
                        ->setParameter('actionid', $options['data']['data'])
                        ->orderBy(UserRepository::ALIAS . '.name', 'ASC');
                },
            ])
            ->add('pilotep', EntityType::class, [
                self::CSS_CLASS => User::class,
                self::CHOICE_LABEL => 'name',
                self::LABEL => ' Pilote du processus',
                self::MULTIPLE => true,
                self::ATTR => [self::CSS_CLASS => 'select2'],
                self::REQUIRED => false,
                self::QUERY_BUILDER => function (EntityRepository $er) use ($options) {
                    return $er->createQueryBuilder(UserRepository::ALIAS)
                        ->select(
                            UserRepository::ALIAS,
                            MProcessRepository::ALIAS,
                            BackpackRepository::ALIAS
                        )
                        ->leftJoin(UserRepository::ALIAS . '.processValidators', MProcessRepository::ALIAS)
                        ->leftJoin(MProcessRepository::ALIAS . '.backpacks', BackpackRepository::ALIAS)
                        ->where(BackpackRepository::ALIAS . '.id = :actionid')
                        ->setParameter('actionid', $options['data']['data'])
                        ->orderBy(UserRepository::ALIAS . '.name', 'ASC');
                },
            ])                       

    ;
    }
}
