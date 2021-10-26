<?php

namespace App\Manager;

use App\Entity\EntityInterface;
use App\Entity\Mailer;
use App\Entity\User;
use App\Helper\SendMail;
use App\Security\CurrentUser;
use App\Validator\MailerValidator;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\Common\Collections\ArrayCollection;

class MailerManager extends AbstractManager
{
    /**
     * @var array
     */
    private $usersTo;

    private $users;

    /**
     * @var CurrentUser
     */
    private $currentUser;


    public function __construct(
        EntityManagerInterface $manager,
        MailerValidator $validator,
        CurrentUser $currentUser
    ) {
        $this->usersTo = new ArrayCollection();
        $this->users = new ArrayCollection();
        $this->currentUser = $currentUser;

        parent::__construct($manager, $validator);
    }

    public function initialise(EntityInterface $entity): void
    {
    }

    public function checkMailer($data): bool
    {
        if (
            empty($data)
            or empty($this->usersTo)
            or empty($data['subject'])
            or empty($data['content'])
        ) {
            return false;
        }

        return true;
    }

    public function initialiseMailer($data): ?Mailer
    {
        if (array_key_exists('admp', $data)) {
            $this->setUsers($data['admp']);
        }
        if (array_key_exists('pilotemp', $data)) {
            $this->setUsers($data['pilotemp']);
        }
        if (array_key_exists('pilotep', $data)) {
            $this->setUsers($data['pilotep']);
        }
        if (!$this->checkMailer($data)) {
            return null;
        }

        /** @var User $userFrom */
        $userFrom = $this->currentUser->getUser();
        $mailer = new Mailer();

        $mailer
            ->setUserFrom($userFrom->getName() . ' [ ' . $userFrom->getEmail() . ' ]')
            ->setUsersTo($this->getUsersTo())
            ->setSubject($data['subject'])
            ->setContent($data['content']);

        return $mailer;
    }

    private function getUsersTo()
    {
        $datas=[];
        foreach($this->users as $user)
        {
            $datas = array_merge($datas, [$user->getName() . ' <small>[' . $user->getEmail() . ']</small>']);
        }
        return implode(';',$datas);
    }

    private function setUsers($data)
    {
        foreach ($data as $user) {
            $this->addUser($user);
        }
    }

    public function getUsersEmailTo()
    {
        return $this->users;
    }

    private function addUser(User $user)
    {
        if ($user->getIsEnable()) {
            if (!$this->users->contains($user)) {
                $this->users[] = $user;
            }
        }
    }

    private function addUserTo(User $user)
    {
        if ($user->getIsEnable()) {
            if (!$this->users->contains($user)) {
                $this->users[] = $user;
            }
        }
    }
}
