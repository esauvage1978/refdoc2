<?php


namespace App\Workflow;


class WorkflowData
{
    const STATE_DRAFT = 'draft';
    const STATE_PUBLISHED = 'published';
    const STATE_ABANDONNED = 'abandonned';
    const STATE_TO_RESUME = 'toResume';

    const TRANSITION_GO_TO_RESUME = 'goToResume';
    const TRANSITION_GO_ABANDONNED = 'goAbandonned';

    const WORKFLOW_IS_SAME = 'same';
    
    private const NAME = 'name';
    private const ICON = 'icon';
    private const TITLE_MAIL = 'title_mail';
    private const BGCOLOR = 'bgcolor';
    private const FORECOLOR = 'forecolor';
    private const TRANSITIONS = 'transitions';

    private static function getStates(): array
    {
        return [
            self::STATE_DRAFT =>
            [
                self::NAME => ' Brouillon',
                self::ICON => 'fab fa-firstdraft',
                self::TITLE_MAIL => ' Un porte-document est passé à l\'état brouillon',
                self::BGCOLOR => '#440155',
                self::FORECOLOR => '#ffffff',
                self::TRANSITIONS => [
                    self::WORKFLOW_IS_SAME => [
                        self::TRANSITION_GO_ABANDONNED
                    ]
                ]
            ],
            self::STATE_ABANDONNED =>
            [
                self::NAME => ' Abandonné',
                self::ICON => 'far fa-trash-alt"',
                self::TITLE_MAIL => ' Un porte-document est abandonné',
                self::BGCOLOR => '#AA0C0C',
                self::FORECOLOR => '#ffffff',
                self::TRANSITIONS => [
                    self::WORKFLOW_IS_SAME => [
                        self::TRANSITION_GO_TO_RESUME
                    ]
                ]
            ],
            self::STATE_TO_RESUME =>
            [
                self::NAME => ' A reprendre',
                self::ICON => 'far fa-edit text-success',
                self::TITLE_MAIL => ' Un porte-document est à repprendre',
                self::BGCOLOR => '#5B2971',
                self::FORECOLOR => '#ffffff',
                self::TRANSITIONS => [
                    self::WORKFLOW_IS_SAME => [
                        self::TRANSITION_GO_ABANDONNED
                    ]
                ]
            ],

        ];
    }

    public static function hasTransition(string $data): bool
    {
        $datas = [
            self::TRANSITION_GO_ABANDONNED,
            self::TRANSITION_GO_TO_RESUME
        ];

        if (in_array($data, $datas)) {
            return true;
        }
        return false;
    }
    public static function hasState(string $data): bool
    {
        $datas = [
            self::STATE_DRAFT,
            self::STATE_TO_RESUME,
            self::STATE_ABANDONNED
        ];

        if (in_array($data, $datas)) {
            return true;
        }
        throw new \InvalidArgumentException('cet état n\'existe pas : ' . $data);
    }
    public static function getNameOfState(string $state)
    {
        return self::getStatesValue($state, self::NAME);
    }

    public static function getIconOfState(string $state)
    {
        return self::getStatesValue($state, self::ICON);
    }

    public static function getTitleOfMail(string $state)
    {
        return self::getStatesValue($state, self::TITLE_MAIL);
    }

    public static function getShortNameOfState(string $state)
    {
        return self::getStatesValue($state, self::NAME);
    }

    public static function getBGColorOfState(string $state)
    {
        return self::getStatesValue($state, self::BGCOLOR);
    }
    public static function getForeColorOfState(string $state)
    {
        return self::getStatesValue($state, self::FORECOLOR);
    }

    private static function  getStatesValue($state, $data)
    {
        if (!self::hasState($state)) {
            throw new \InvalidArgumentException('cet état n\'existe pas : ' . $state);
        }
        return self::getStates()[$state][$data];
    }

    public static function getTransitionsForState($workflow, $state)
    {
        return self::getStatesValueForWorkfow($workflow, $state, self::TRANSITIONS);
    }

    private static function  getStatesValueForWorkfow($workflow, $state, $data)
    {
        if (!self::hasState($state)) {
            throw new \InvalidArgumentException('cet état n\'existe pas : ' . $state);
        }
        if (array_key_exists(self::WORKFLOW_IS_SAME,  self::getStates()[$state][$data])) {
            return self::getStates()[$state][$data][self::WORKFLOW_IS_SAME];
        } else {
            return self::getStates()[$state][$data][$workflow];
        }
    }
    public static function getModalDataForTransition(string $transition)
    {
        if (!self::hasTransition($transition)) {
            throw new \InvalidArgumentException('Cette transition n\'existe pas : ' . $transition);
        }
        $data = [
            'state' => '',
            'transition' => $transition,
            'titre' => '',
            'btn_label' => ''
        ];

        switch ($transition) {

            case self::TRANSITION_GO_ABANDONNED:
                $data['state'] = self::STATE_ABANDONNED;
                $data['titre'] = 'Abandonner le porte-document';
                $data['btn_label'] = 'Abandonner';
                break;
            case self::TRANSITION_GO_TO_RESUME:
                $data['state'] = self::STATE_TO_RESUME;
                $data['titre'] = 'Le document est à reprendre';
                $data['btn_label'] = 'A reprendre';
                break;
        }

        return $data;
    }
}
