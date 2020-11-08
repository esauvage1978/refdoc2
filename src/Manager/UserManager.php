<?php

declare(strict_types=1);

namespace App\Manager;

use DateTime;
use function md5;
use function copy;
use function rand;
use App\Entity\User;
use function uniqid;
use function unlink;
use function explode;

use function is_null;
use App\Security\Role;
use function in_array;
use function date_format;
use function file_exists;
use function str_replace;
use App\Entity\UserParam;
use function random_bytes;
use function base64_decode;
use function file_put_contents;
use App\Helper\ParamsInServices;
use App\Validator\UserValidator;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class UserManager
{
    /** @var UserPasswordEncoderInterface */
    private $passwordEncoder;

    /** @var EntityManagerInterface */
    private $manager;

    /** @var UserValidator */
    private $validator;

    /** @var ParamsInServices */
    private $params;

    /** @var UserRepository */
    private $userRepository;

    public function __construct(
        EntityManagerInterface $manager,
        UserValidator $validator,
        UserPasswordEncoderInterface $passwordEncoder,
        UserRepository $userRepository,
        ParamsInServices $params
    ) {
        $this->manager = $manager;
        $this->validator = $validator;
        $this->passwordEncoder = $passwordEncoder;
        $this->userRepository = $userRepository;
        $this->params = $params;
    }

    public function save(User $user, $oldUserMail = null): bool
    {
        $this->initialise($user, $oldUserMail);

        if (! $this->validator->isValid($user)) {
            return false;
        }

        $this->manager->persist($user);
        $this->manager->flush();

        $this->actionAfterSave($user);

        return true;
    }

    private function actionAfterSave($item): void
    {
        $this->checkAvatar($item);
    }

    public function initialise(User $user, $oldUserMail = null)
    {
        $this->encodePassword($user);

        if ($user->getCreatedAt() === null) {
            $user->setCreatedAt(new DateTime());
            $user->setisEnable(true);
        } else {
            $user->setModifiedAt(new DateTime());
        }

        if (
            ! $user->getEmailValidatedToken() or
            ($user->getEmail() !== $oldUserMail and $oldUserMail !== null)
        ) {
            $user
                ->setEmailValidated(false)
                ->setEmailValidatedToken(md5(random_bytes(50)));
        }

        $this->checkAvatar($user);

        if(null == $user->getUserParam()) {
            $user->setUserParam((new UserParam()));
        }

        return true;
    }

    public function checkAvatar(User $user): bool
    {
        if (is_null($user->getId())) {
            return false;
        }

        if (! file_exists($this->params->get(ParamsInServices::ES_DIRECTORY_AVATAR) . '/' . $user->getId() . '.png')) {
            copy(
                $this->params->get(ParamsInServices::ES_DIRECTORY_AVATAR) . '/__default_' . rand(1, 16) . '.png',
                $this->params->get(ParamsInServices::ES_DIRECTORY_AVATAR) . '/' . $user->getId() . '.png'
            );
        }

        return true;
    }

    public function changeAvatar(User $user, $image): void
    {
        $image = str_replace(' ', '+', $image);
        [$type, $data] = explode(';', $image);
        [, $data] = explode(',', $data);
        $data = base64_decode($data);
        if (file_exists($this->params->get(ParamsInServices::ES_DIRECTORY_AVATAR) . '/' . $user->getId() . '.png')) {
            unlink($this->params->get(ParamsInServices::ES_DIRECTORY_AVATAR) . '/' . $user->getId() . '.png');
        }

        file_put_contents(
            $this->params->get(ParamsInServices::ES_DIRECTORY_AVATAR) . '/' . $user->getId() . '.png',
            $data
        );
    }

    public function checkPassword($user, $pwd): bool
    {
        return $this->passwordEncoder->isPasswordValid($user, $pwd);
    }

    public function encodePassword(User $user): bool
    {
        $plainPassword = $user->getPlainPassword();

        if (empty($user->getPassword()) and empty($plainPassword)) {
            $plainPassword = uniqid();
        }

        if ($plainPassword) {
            $user->setPassword(
                $this->passwordEncoder->encodePassword(
                    $user,
                    $plainPassword
                )
            );
        }

        return true;
    }

    public function getErrors(User $entity)
    {
        return $this->validator->getErrors($entity);
    }

    public function remove(User $entity): void
    {
        $this->manager->remove($entity);
        $this->manager->flush();
    }

    public function validateEmail(User $user)
    {
        $user->setEmailValidated(true);
        $user->setEmailValidatedToken(date_format(new DateTime(), 'Y-m-d H:i:s'));
        if (! in_array(Role::ROLE_USER, $user->getRoles())) {
            $user->setRoles([Role::ROLE_USER]);
        }

        return $this;
    }

    public function onConnected(User $user): bool
    {
        $user->setLoginAt(new DateTime());

        return true;
    }

    public function initialisePasswordForget(User $user): bool
    {
        $user->setForgetToken(md5(random_bytes(50)));

        return true;
    }

    public function initialisePasswordRecover(User $user, string $plainPassword, string $plainPasswordConfirmm): bool
    {
        $user->setForgetToken(date_format(new DateTime(), 'Y-m-d H:i:s'));
        $user->setPlainPassword($plainPassword);
        $user->setPlainPasswordConfirmation($plainPasswordConfirmm);

        return true;
    }

    public function initialisePasswordChange(User $user, string $plainPassword, string $plainPasswordConfirm): bool
    {
        $user->setPlainPassword($plainPassword);
        $user->setPlainPasswordConfirmation($plainPasswordConfirm);

        return true;
    }
}
