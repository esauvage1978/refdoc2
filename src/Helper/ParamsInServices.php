<?php

declare(strict_types=1);

namespace App\Helper;

use InvalidArgumentException;
use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;

use function in_array;

/**
 * Récupération des paramètres présents dans le fichier config/service.yaml
 *
 * (c) Emmanuel Sauvage <emmanuel.sauvage@live.fr>
 * 24/07/2020
 *
 */
class ParamsInServices
{
    public const ES_APP_NAME = 'es.app.name';
    public const ES_DIRECTORY_AVATAR = 'es.directory.avatar';
    public const ES_DIRECTORY_CSS = 'es.directory.css';
    public const ES_DIRECTORY_UPLOAD_BACKPACK = 'es.directory.upload.backpack';
    public const ES_MAILER_OBJECT_PREFIXE = 'es.mailer.object.prefixe';
    public const ES_MAILER_USER_NAME = 'es.mailer.user.name';
    public const ES_MAILER_USER_MAIL = 'es.mailer.user.mail';
    public const ES_MAILER_USER_PASSWORD = 'es.mailer.user.password';
    public const ES_MAILER_SMTP_HOST = 'es.mailer.smtp.host';
    public const ES_MAILER_SMTP_PORT = 'es.mailer.smtp.port';
    public const ES_NEWS_TIME = 'es.news.time';
    public const ES_TREE_UNDEVELOPPED_NBR = 'es.tree.undevelopped.nbr';
    public const ES_MAILER_WORKFLOW_TORESUME = 'es.mailer.workflow.toResume';
    public const ES_MAILER_WORKFLOW_TOVALIDATE = 'es.mailer.workflow.toValidate';
    public const ES_MAILER_WORKFLOW_TOCONTROL = 'es.mailer.workflow.toControl';
    public const ES_MAILER_WORKFLOW_TOCHECK = 'es.mailer.workflow.toCheck';

    /** @var ParameterBagInterface */
    private $params;

    /** @var array $datas */
    private $datas = [];

    public function __construct(ParameterBagInterface $params)
    {
        $this->params = $params;
        $this->datas = [
            self::ES_APP_NAME,
            self::ES_DIRECTORY_AVATAR,
            self::ES_DIRECTORY_CSS,
            self::ES_DIRECTORY_UPLOAD_BACKPACK,
            self::ES_MAILER_OBJECT_PREFIXE,
            self::ES_MAILER_USER_NAME,
            self::ES_MAILER_USER_MAIL,
            self::ES_MAILER_USER_PASSWORD,
            self::ES_MAILER_SMTP_HOST,
            self::ES_MAILER_SMTP_PORT,
            self::ES_NEWS_TIME,
            self::ES_TREE_UNDEVELOPPED_NBR,
            self::ES_MAILER_WORKFLOW_TORESUME,
            self::ES_MAILER_WORKFLOW_TOVALIDATE,
            self::ES_MAILER_WORKFLOW_TOCONTROL,
            self::ES_MAILER_WORKFLOW_TOCHECK,
        ];
    }

    /**
     * Récupère la valeur paramètre présente dans le fichiers config/services.yaml.
     * Utiliser les constantes présentes dans cette classe
     *
     * @param string $param_name
     * @return string
     */
    public function get(string $param_name): string
    {
        if (!in_array($param_name, $this->datas)) {
            throw new InvalidArgumentException('Ce paramètre est inconnu : ' . $param_name);
        }

        return $this->params->get($param_name);
    }
}
