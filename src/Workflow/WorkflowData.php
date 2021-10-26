<?php


namespace App\Workflow;


class WorkflowData
{
    const STATE_DRAFT = 'draft';
    const STATE_TO_VALIDATE = 'toValidate';
    const STATE_TO_CONTROL = 'toControl';
    const STATE_TO_CHECK = 'toCheck';
    const STATE_PUBLISHED = 'published';
    const STATE_ABANDONNED = 'abandonned';
    const STATE_TO_RESUME = 'toResume';
    const STATE_TO_REVISE = 'toRevise';
    const STATE_IN_REVIEW = 'inReview';

    const TRANSITION_GO_TO_RESUME = 'goToResume';
    const TRANSITION_GO_TO_REVISE = 'goToRevise';
    const TRANSITION_GO_IN_REVIEW = 'goInReview';
    const TRANSITION_GO_TO_VALIDATE = 'goToValidate';
    const TRANSITION_GO_TO_CONTROL = 'goToControl';
    const TRANSITION_GO_TO_CHECK = 'goToCheck';
    const TRANSITION_GO_ABANDONNED = 'goAbandonned';
    const TRANSITION_GO_PUBLISHED = 'goPublished';

    const WORKFLOW_IS_SAME = 'same';
    
    private const NAME = 'name';
    private const NAME_PERSO = 'name_persi';
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
                self::NAME_PERSO => ' Brouillon',
                self::ICON => 'fab fa-firstdraft',
                self::TITLE_MAIL => ' Un porte-document est passé à l\'état brouillon',
                self::BGCOLOR => '#440155',
                self::FORECOLOR => '#ffffff',
                self::TRANSITIONS => [
                    self::WORKFLOW_IS_SAME => [
                        self::TRANSITION_GO_TO_VALIDATE,
                        self::TRANSITION_GO_ABANDONNED
                    ]
                ]
            ],
            self::STATE_TO_VALIDATE =>
            [
                self::NAME => ' A valider',
                self::NAME_PERSO => ' J\'envoie en validation',
                self::ICON => 'fas fa-stamp',
                self::TITLE_MAIL => ' Un porte-document est à valider par les responsables hiérarchiques',
                self::BGCOLOR => '#5b0570',
                self::FORECOLOR => '#ffffff',
                self::TRANSITIONS => [
                    WorkflowNames::WORKFLOW_ALL => [
                        self::TRANSITION_GO_TO_CONTROL,
                        self::TRANSITION_GO_TO_RESUME,
                        self::TRANSITION_GO_ABANDONNED,
                    ],
                    WorkflowNames::WORKFLOW_WITHOUT_DOC => [
                        self::TRANSITION_GO_TO_CONTROL,
                        self::TRANSITION_GO_TO_RESUME,
                        self::TRANSITION_GO_ABANDONNED
                    ],
                    WorkflowNames::WORKFLOW_WITHOUT_CONTROL => [
                        self::TRANSITION_GO_TO_CHECK,
                        self::TRANSITION_GO_TO_RESUME,
                        self::TRANSITION_GO_ABANDONNED
                    ],
                    WorkflowNames::WORKFLOW_WITHOUT_DOCCONTROL => [
                        self::TRANSITION_GO_PUBLISHED,
                        self::TRANSITION_GO_TO_RESUME,
                        self::TRANSITION_GO_ABANDONNED
                    ],
                ]
            ],
            self::STATE_TO_CONTROL =>
            [
                self::NAME => ' A contrôler',
                self::NAME_PERSO => ' J\'envoie au contrôle',
                self::ICON => 'fas fa-copyright',
                self::TITLE_MAIL => ' Un porte-document est à valider par le service contrôle',
                self::BGCOLOR => '#794A8D',
                self::FORECOLOR => '#ffffff',
                self::TRANSITIONS => [
                    WorkflowNames::WORKFLOW_ALL => [
                        self::TRANSITION_GO_TO_CHECK,
                        self::TRANSITION_GO_TO_RESUME,
                        self::TRANSITION_GO_ABANDONNED,
                    ],
                    WorkflowNames::WORKFLOW_WITHOUT_DOC => [
                        self::TRANSITION_GO_PUBLISHED,
                        self::TRANSITION_GO_TO_RESUME,
                        self::TRANSITION_GO_ABANDONNED
                    ],
                    WorkflowNames::WORKFLOW_WITHOUT_CONTROL => [
                        self::TRANSITION_GO_TO_RESUME,
                        self::TRANSITION_GO_ABANDONNED
                    ],
                    WorkflowNames::WORKFLOW_WITHOUT_DOCCONTROL => [
                        self::TRANSITION_GO_TO_RESUME,
                        self::TRANSITION_GO_ABANDONNED
                    ],

                ]
            ],            
            self::STATE_ABANDONNED =>
            [
                self::NAME => ' Abandonné',
                self::NAME_PERSO => ' J\'abandonne',
                self::ICON => 'far fa-trash-alt',
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
                self::NAME_PERSO => ' Je renvoie à l\'émetteur',
                self::ICON => 'far fa-edit',
                self::TITLE_MAIL => ' Un porte-document est à reprendre',
                self::BGCOLOR => '#5B2971',
                self::FORECOLOR => '#ffffff',
                self::TRANSITIONS => [
                    self::WORKFLOW_IS_SAME => [
                        self::TRANSITION_GO_TO_VALIDATE,
                        self::TRANSITION_GO_ABANDONNED
                    ]
                ]
            ],
            self::STATE_TO_REVISE =>
            [
                self::NAME => ' A réviser',
                self::NAME_PERSO => ' J\'envoie en révision',
                self::ICON => 'fas fa-recycle',
                self::TITLE_MAIL => ' Un porte-document est à réviser',
                self::BGCOLOR => '#E851BB',
                self::FORECOLOR => '#ffffff',
                self::TRANSITIONS => [
                    self::WORKFLOW_IS_SAME => [
                        self::TRANSITION_GO_IN_REVIEW,
                        self::TRANSITION_GO_ABANDONNED
                    ]
                ]
            ],
            self::STATE_IN_REVIEW =>
            [
                self::NAME => ' En révision',
                self::NAME_PERSO => ' En révision',
                self::ICON => 'fas fa-crosshairs',
                self::TITLE_MAIL => ' Un porte-document est à réviser',
                self::BGCOLOR => '#ED59FF',
                self::FORECOLOR => '#ffffff',
                self::TRANSITIONS => [
                    self::WORKFLOW_IS_SAME => [
                        self::TRANSITION_GO_ABANDONNED
                    ]
                ]
            ],           
            self::STATE_TO_CHECK =>
            [
                self::NAME => ' A vérifier par la documentation',
                self::NAME_PERSO => ' J\'envoie au service documentation',
                self::ICON => 'fas fa-barcode',
                self::TITLE_MAIL => ' Un porte-document est à valider par le service documentation',
                self::BGCOLOR => '#9974AA',
                self::FORECOLOR => '#ffffff',
                self::TRANSITIONS => [
                    WorkflowNames::WORKFLOW_ALL => [
                        self::TRANSITION_GO_PUBLISHED,
                        self::TRANSITION_GO_TO_RESUME,
                        self::TRANSITION_GO_ABANDONNED
                    ],
                    WorkflowNames::WORKFLOW_WITHOUT_DOCCONTROL => [
                        self::TRANSITION_GO_TO_RESUME,
                        self::TRANSITION_GO_ABANDONNED
                    ],
                    WorkflowNames::WORKFLOW_WITHOUT_CONTROL => [
                        self::TRANSITION_GO_PUBLISHED,
                        self::TRANSITION_GO_TO_RESUME,
                        self::TRANSITION_GO_ABANDONNED
                    ],
                    WorkflowNames::WORKFLOW_WITHOUT_DOC => [
                        self::TRANSITION_GO_TO_RESUME,
                        self::TRANSITION_GO_ABANDONNED
                    ],
                ]
            ],
            self::STATE_PUBLISHED =>
            [
                self::NAME => ' Publié',
                self::NAME_PERSO => ' Je publie',
                self::ICON => 'fab fa-product-hunt',
                self::TITLE_MAIL => ' Un porte-document est publié',
                self::BGCOLOR => '#ff6584',
                self::FORECOLOR => '#ffffff',
                self::TRANSITIONS => [
                    self::WORKFLOW_IS_SAME => [
                        self::TRANSITION_GO_TO_REVISE,
                        self::TRANSITION_GO_ABANDONNED
                    ]
                ]
            ],
        ];
    }

    public static function hasTransition(string $data): bool
    {
        $datas = [
            self::TRANSITION_GO_TO_VALIDATE,
            self::TRANSITION_GO_ABANDONNED,
            self::TRANSITION_GO_TO_RESUME,
            self::TRANSITION_GO_TO_CONTROL,
            self::TRANSITION_GO_TO_CHECK,
            self::TRANSITION_GO_PUBLISHED,
            self::TRANSITION_GO_TO_REVISE,
            self::TRANSITION_GO_IN_REVIEW
        ];

        if (in_array($data, $datas)) {
            return true;
        }
        return false;
    }

    public static function hasState(string $data): bool
    {
        $datas = [
            self::STATE_TO_VALIDATE,
            self::STATE_DRAFT,
            self::STATE_TO_RESUME,
            self::STATE_ABANDONNED,
            self::STATE_TO_CONTROL,
            self::STATE_TO_CHECK,
            self::STATE_PUBLISHED,
            self::STATE_TO_REVISE,
            self::STATE_IN_REVIEW
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

    public static function getShortNamePersoOfState(string $state)
    {
        return self::getStatesValue($state, self::NAME_PERSO);
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
            case self::TRANSITION_GO_TO_VALIDATE:
                $data['state'] = self::STATE_TO_VALIDATE;
                $data['titre'] = 'Mettre à la validation hiérarchique';
                $data['btn_label'] = self::getStatesValue(self::STATE_TO_VALIDATE, self::NAME_PERSO);
                break;
            case self::TRANSITION_GO_ABANDONNED:
                $data['state'] = self::STATE_ABANDONNED;
                $data['titre'] = 'Abandonner le porte-document';
                $data['btn_label'] = self::getStatesValue(self::STATE_ABANDONNED, self::NAME_PERSO);
                break;
            case self::TRANSITION_GO_TO_RESUME:
                $data['state'] = self::STATE_TO_RESUME;
                $data['titre'] = 'Le document est à reprendre';
                $data['btn_label'] = self::getStatesValue(self::STATE_TO_RESUME, self::NAME_PERSO);
                break;
            case self::TRANSITION_GO_TO_CONTROL:
                $data['state'] = self::STATE_TO_CONTROL;
                $data['titre'] = 'Mettre à la validation du service contrôle';
                $data['btn_label'] = self::getStatesValue(self::STATE_TO_CONTROL, self::NAME_PERSO);
                break;
            case self::TRANSITION_GO_TO_CHECK:
                $data['state'] = self::STATE_TO_CHECK;
                $data['titre'] = 'Vérifier la forme des documents';
                $data['btn_label'] = self::getStatesValue(self::STATE_TO_CHECK, self::NAME_PERSO);
                break;
            case self::TRANSITION_GO_PUBLISHED:
                $data['state'] = self::STATE_PUBLISHED;
                $data['titre'] = 'Publier le document';
                $data['btn_label'] = self::getStatesValue(self::STATE_PUBLISHED, self::NAME_PERSO);
                break;
            case self::TRANSITION_GO_TO_REVISE:
                $data['state'] = self::STATE_TO_REVISE;
                $data['titre'] = 'Mettre à réviser';
                $data['btn_label'] = self::getStatesValue(self::STATE_TO_REVISE, self::NAME_PERSO);
                break;
            case self::TRANSITION_GO_IN_REVIEW:
                $data['state'] = self::STATE_IN_REVIEW;
                $data['titre'] = 'Mettre en révision';
                $data['btn_label'] = self::getStatesValue(self::STATE_IN_REVIEW, self::NAME_PERSO);
                break;                                 
        }

        return $data;
    }
}
