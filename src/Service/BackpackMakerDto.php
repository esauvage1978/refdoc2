<?php

namespace App\Service;

use App\Dto\BackpackDto;
use App\Dto\RubricDto;
use App\Dto\UnderRubricDto;
use App\Dto\UserDto;
use App\Entity\User;
use App\Repository\BackpackDtoRepository;
use App\Security\Role;
use App\Workflow\WorkflowData;

class BackpackMakerDto
{
    const HOME_SUBSCRIPTION = 'home_subscription';
    const HOME_ALL = 'home_all';

    Const DRAFT='draft';
    Const MY_DRAFT_UPDATABLE= 'mydraft_updatable';
    const DRAFT_UPDATABLE = 'draft_updatable';

    const TO_VALIDATE = 'to_validate';
    Const PUBLISHED='published';
    Const SEARCH='search';
    Const PUBLISHED_FOR_RUBRIC='published_for_rubric';
    Const PUBLISHED_FOR_UNDERRUBRIC='published_for_underrubric';
    Const NEWS='news';
    Const NEWS_FOR_RUBRIC='news_for_rubric';
    Const NEWS_FOR_UNDERRUBRIC='news_for_underrubric';

    Const ABANDONNED='abandonned';
    const ABANDONNED_UPDATABLE = 'abandonned_updatable';
    const MY_ABANDONNED_UPDATABLE = 'myabandonned_updatable';

    public const TO_RESUME = 'toResume';
    public const TO_RESUME_UPDATABLE = 'toResume_updatable';
    public const MY_TO_RESUME_UPDATABLE = 'mytoResume_updatable';

    Const HIDE='hide';

    /**
     * @var User
     */
    private $user;

    /**
     * @var bool
     */
    private $gestionnaire;

    public function __construct(?User $user)
    {
        $this->user = $user;
        $this->gestionnaire = Role::isGestionnaire( $this->user);
    }


    public function get(string $type,?string $param=null): BackpackDto
    {
        $dto = new BackpackDto();
        $dto = $this->checkUser($dto);
        switch ($type)
        {
            case self::HOME_SUBSCRIPTION:
                if (!is_null($this->user)) {
                    $dto->setUserDto((new UserDto())->setId($this->user->getId()));
                }
                $dto
                    ->setStateCurrent(WorkflowData::STATE_DRAFT)
                    ->setIsForSubscription(BackpackDto::TRUE)
                    ->setVisible(BackpackDto::TRUE);
                break;                 
            case self::DRAFT:
                $dto
                    ->setStateCurrent(WorkflowData::STATE_DRAFT)
                    ->setVisible(BackpackDto::TRUE);
                break;
            case self::DRAFT_UPDATABLE:
                if (!is_null($this->user)) {
                    $dto->setUserDto((new UserDto())->setId($this->user->getId()));
                }
                $dto
                    ->setStateCurrent(WorkflowData::STATE_DRAFT)
                    ->setIsUpdatable(BackpackDto::TRUE)
                    ->setVisible(BackpackDto::TRUE);
                break;                
            case self::MY_DRAFT_UPDATABLE:
                if (!is_null($this->user)) {
                    $dto->setOwnerDto((new UserDto())->setId($this->user->getId()));
                }
                $dto
                    ->setIsUpdatable(BackpackDto::TRUE)
                    ->setStateCurrent(WorkflowData::STATE_DRAFT)
                    ->setVisible(BackpackDto::TRUE);
                break;
            case self::ABANDONNED:
                $dto
                    ->setStateCurrent(WorkflowData::STATE_ABANDONNED)
                    ->setVisible(BackpackDto::TRUE);
                break;
            case self::ABANDONNED_UPDATABLE:
                if (!is_null($this->user)) {
                    $dto->setUserDto((new UserDto())->setId($this->user->getId()));
                }
                $dto
                    ->setStateCurrent(WorkflowData::STATE_ABANDONNED)
                    ->setIsUpdatable(BackpackDto::TRUE)
                    ->setVisible(BackpackDto::TRUE);
                break;
            case self::MY_ABANDONNED_UPDATABLE:
                if (!is_null($this->user)) {
                    $dto->setOwnerDto((new UserDto())->setId($this->user->getId()));
                }
                $dto
                    ->setIsUpdatable(BackpackDto::TRUE)
                    ->setStateCurrent(WorkflowData::STATE_ABANDONNED)
                    ->setVisible(BackpackDto::TRUE);
                break;
            case self::TO_RESUME:
                $dto
                    ->setStateCurrent(WorkflowData::STATE_TO_RESUME)
                    ->setVisible(BackpackDto::TRUE);
                break;
            case self::TO_RESUME_UPDATABLE:
                if (!is_null($this->user)) {
                    $dto->setUserDto((new UserDto())->setId($this->user->getId()));
                }
                $dto
                    ->setStateCurrent(WorkflowData::STATE_TO_RESUME)
                    ->setIsUpdatable(BackpackDto::TRUE)
                    ->setVisible(BackpackDto::TRUE);
                break;
            case self::MY_TO_RESUME_UPDATABLE:
                if (!is_null($this->user)) {
                    $dto->setOwnerDto((new UserDto())->setId($this->user->getId()));
                }
                $dto
                    ->setIsUpdatable(BackpackDto::TRUE)
                    ->setStateCurrent(WorkflowData::STATE_TO_RESUME)
                    ->setVisible(BackpackDto::TRUE);
                break;                                                     
        }

        return $dto;
    }


    private function checkUser(BackpackDto $dto)
    {
        if (!is_null($this->user) && !$this->gestionnaire) {
            $dto->setUserDto((new UserDto())->setId($this->user->getId()));
        }
        return $dto;
    }

}
