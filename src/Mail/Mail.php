<?php

declare(strict_types=1);

namespace App\Mail;

use App\Entity\User;
use App\Helper\ParamsInServices;
use Symfony\Component\Mailer\Exception\TransportExceptionInterface;
use Symfony\Component\Mailer\Transport\Smtp\EsmtpTransport;
use Twig\Environment;
use Swift_Mailer;
use Swift_Message;

use function array_merge;
use function dump;
use function in_array;

/**
 * Classe permettant l'envoi d'un mail 
 *
 * (c) Emmanuel Sauvage <emmanuel.sauvage@live.fr>
 * 24/07/2020
 *
 */
class Mail
{
    public const USERS_TO = 'users_to';

    /** @var array */
    private $usersTo;

    /** @var array */
    private $userFrom;

    /** @var Environment */
    private $twig;

    /** @var ParamsInServices */
    private $paramsInServices;

    private $subject;

    private $context;

    private $paramsTwig;

    /**
     * @var Swift_Mailer
     */
    private $mailer;

    public function __construct(
        Environment $twig,
        ParamsInServices $paramsInServices,
        Swift_Mailer $mailer
    ) {
        $this->twig = $twig;
        $this->paramsInServices = $paramsInServices;
        $this->mailer = $mailer;
    }

    public function send(): int
    {
        $email = (new Swift_Message())
            ->setFrom($this->getUserFrom())
            ->setTo($this->getUserTo())
            ->setSubject($this->getSubject())
            ->setBody($this->getHtml(), 'text/html');


        try {
            $this->mailer->send($email);

            return 1;
        } catch (TransportExceptionInterface $e) {
            dump('error to send mail : ' . $e->getMessage());

            return 0;
        }
    }

    //######################################
    //   HTML
    //######################################

    private function getHtml()
    {
        return $this->twig->render('mail/' . $this->getContext() . '.html.twig', $this->getParamsTwig());
    }

    //######################################
    //   CONTEXT
    //######################################

    /**
     * @return mixed
     */
    private function getParamsTwig()
    {
        if (!in_array('application_name', $this->paramsTwig)) {
            $this->paramsTwig = array_merge(
                $this->paramsTwig,
                ['application_name' => $this->paramsInServices->get(ParamsInServices::ES_APP_NAME)]
            );
        }

        return $this->paramsTwig;
    }

    /**
     * @param mixed $paramsTwig
     */
    public function setParamsTwig($paramsTwig): Mail
    {
        $this->paramsTwig = $paramsTwig;

        return $this;
    }

    //######################################
    //   CONTEXT
    //######################################

    public function setContext($context): Mail
    {
        $this->context = $context;

        return $this;
    }

    private function getContext()
    {
        return empty($this->context)
            ? 'default'
            : $this->context;
    }

    //######################################
    //   SUBJECT
    //######################################

    public function setSubject($subject): Mail
    {
        $this->subject = $subject;

        return $this;
    }

    private function getSubject()
    {
        return $this->paramsInServices->get(ParamsInServices::ES_MAILER_OBJECT_PREFIXE) . ' ' . (empty($this->subject)
            ? 'Pas d\'objet'
            : $this->subject);
    }

    //######################################
    //   USER TO
    //######################################

    public function setUserTo(User $user): Mail
    {
        $this->usersTo = [$user->getEmail()=> $user->getUsername()];

        return $this;
    }

    private function getUserTo()
    {
        if (in_array(self::USERS_TO, $this->paramsTwig)) {
            foreach ($this->paramsTwig[self::USERS_TO] as $user) {
                $this->usersTo = array_merge(
                    $this->usersTo,
                    [$user->getEmail()=> $user->getUsername()]
                );
            }
        }


        return empty($this->usersTo)
            ? $this->getContactApp()
            : $this->usersTo;
    }

    //######################################
    //   USER FROM
    //######################################

    public function setUserFrom(User $user): Mail
    {
        $this->userFrom = [ $user->getEmail()=> $user->getUsername()];

        return $this;
    }

    private function getUserFrom()
    {
        return empty($this->userFrom)
            ? $this->getContactApp()
            : $this->userFrom;
    }


    //######################################
    //   CONTACT APP
    //######################################
    private function getContactApp()
    {
        $contact_mail = $this->paramsInServices->get(ParamsInServices::ES_MAILER_USER_MAIL);
        $contact_name = $this->paramsInServices->get(ParamsInServices::ES_MAILER_USER_NAME);

        return [$contact_mail=> $contact_name];
    }
}
