<?php

declare(strict_types=1);

namespace App\Workflow;

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
class WorkflowNames
{
    public const WORKFLOW_ALL = 'wkf_all';
    public const WORKFLOW_WITHOUT_DOC = 'wkf_without_doc';
    public const WORKFLOW_WITHOUT_CONTROL = 'wkf_without_control';
    public const WORKFLOW_WITHOUT_DOCCONTROL = 'wkf_without_doccontrol';


    /** @var array $datas */
    private $datas = [
        self::WORKFLOW_ALL,
        self::WORKFLOW_WITHOUT_DOC,
        self::WORKFLOW_WITHOUT_CONTROL,
        self::WORKFLOW_WITHOUT_DOCCONTROL
    ];

    /**
     * Permet de valider le nom du workflow
     *
     * @param string $workflow_name
     * @return string
     */
    public function check(string $workflow_name): string
    {
        if (!in_array($workflow_name, $this->datas)) {
            throw new InvalidArgumentException('Ce workflow est inconnu : ' . $workflow_name);
        }

        return $workflow_name;
    }
}
