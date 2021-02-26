<?php

declare(strict_types=1);

namespace App\Mail;

use App\Entity\User;
use App\Helper\ParamsInServices;
use Symfony\Component\Mailer\Exception\TransportExceptionInterface;
use Symfony\Component\Mailer\Mailer;
use Symfony\Component\Mailer\Transport\Smtp\EsmtpTransport;
use Symfony\Component\Mime\Address;
use Symfony\Component\Mime\Email;
use Twig\Environment;

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

    /** @var EsmtpTransport */
    private $transport;

    /** @var ParamsInServices */
    private $paramsInServices;

    private $subject;

    private $context;

    private $paramsTwig;

    public function __construct(
        Environment $twig,
        ParamsInServices $paramsInServices,
        MailerTransport $mailerTransport
    ) {
        $this->twig = $twig;
        $this->paramsInServices = $paramsInServices;
        $this->transport = $mailerTransport->getTransport();
    }

    public function send(): int
    {
        $email = (new Email())
            ->from($this->getUserFrom())
            ->to($this->getUserTo())
            ->priority(Email::PRIORITY_HIGH)
            ->subject($this->getSubject())
            ->html($this->getHtml());

        $mailer = new Mailer($this->transport);

        try {
            $mailer->send($email);

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
        $this->usersTo =  new Address($user->getEmail(), $user->getUsername());

        return $this;
    }

    private function getUserTo()
    {
        if (in_array(self::USERS_TO, $this->paramsTwig)) {
            foreach ($this->paramsTwig[self::USERS_TO] as $user) {
                $this->usersTo = array_merge(
                    $this->usersTo,
                    [new Address($user->getEmail(), $user->getUsername())]
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
        $this->userFrom =  [new Address($user->getEmail(), $user->getUsername())];

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
    private function getContactApp(): Address
    {
        $contact_mail = $this->paramsInServices->get(ParamsInServices::ES_MAILER_USER_MAIL);
        $contact_name = $this->paramsInServices->get(ParamsInServices::ES_MAILER_USER_NAME);

        return new Address($contact_mail, $contact_name);
    }
}
