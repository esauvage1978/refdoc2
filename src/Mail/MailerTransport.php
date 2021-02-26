<?php

declare(strict_types=1);

namespace App\Mail;

use App\Helper\ParamsInServices;
use Symfony\Component\Mailer\Transport\Smtp\EsmtpTransport;

/**
 * Initialisation du transport SMTP
 *
 * (c) Emmanuel Sauvage <emmanuel.sauvage@live.fr>
 * 24/07/2020
 *
 */
class MailerTransport
{
    /** @var EsmtpTransport */
    private $transport;

    /** @var ParamsInServices */
    private $paramsInServices;

    public function __construct(ParamsInServices $paramsInServices)
    {
        $this->paramsInServices = $paramsInServices;
    }

    public function getTransport()
    {
        $smtp_port = intval($this->paramsInServices->get(ParamsInServices::ES_MAILER_SMTP_PORT));
        $smtp_host = $this->paramsInServices->get(ParamsInServices::ES_MAILER_SMTP_HOST);

        $this->transport = new EsmtpTransport($smtp_host, $smtp_port);

        $this->transport
            ->setUsername($this->paramsInServices->get(ParamsInServices::ES_MAILER_USER_NAME))
            ->setPassword($this->paramsInServices->get(ParamsInServices::ES_MAILER_USER_PASSWORD));

        return $this->transport;
    }
}
