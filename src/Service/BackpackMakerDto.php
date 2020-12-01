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

    public const BACKPACK_IN_PROGRESS = 'backpack_in_progress';

    const SEARCH = 'search';

    const DRAFT = 'draft';
    const MY_DRAFT_UPDATABLE = 'mydraft_updatable';
    const DRAFT_UPDATABLE = 'draft_updatable';

    const ABANDONNED = 'abandonned';
    const ABANDONNED_UPDATABLE = 'abandonned_updatable';
    const MY_ABANDONNED_UPDATABLE = 'myabandonned_updatable';

    public const TO_RESUME = 'toResume';
    public const TO_RESUME_UPDATABLE = 'toResume_updatable';
    public const MY_TO_RESUME_UPDATABLE = 'mytoResume_updatable';

    public const TO_VALIDATE = 'toValidate';
    public const TO_VALIDATE_UPDATABLE = 'toValidate_updatable';
    public const MY_TO_VALIDATE_UPDATABLE = 'mytoValidate_updatable';

    public const TO_CONTROL = 'toControl';

    public const TO_CHECK = 'toCheck';

    const HIDE = 'hide';

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
        $this->gestionnaire = Role::isGestionnaire($this->user);
    }


    public function get(string $type, ?string $param = null): BackpackDto
    {
        $dto = new BackpackDto();
        $dto = $this->checkUser($dto);
        switch ($type) {
            case self::BACKPACK_IN_PROGRESS:
                if (!is_null($this->user)) {
                    $dto->setOwnerDto((new UserDto())->setId($this->user->getId()));
                }
                $dto
                    ->setStatInProgress(BackpackDto::TRUE)
                    ->setVisible(BackpackDto::TRUE);
                break;
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
                    ->setIsContributor(BackpackDto::TRUE)
                    ->setVisible(BackpackDto::TRUE);
                break;
            case self::MY_DRAFT_UPDATABLE:
                if (!is_null($this->user)) {
                    $dto->setOwnerDto((new UserDto())->setId($this->user->getId()));
                }
                $dto
                    ->setStateCurrent(WorkflowData::STATE_DRAFT)
                    ->setIsContributor(BackpackDto::TRUE)
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
                    ->setIsContributor(BackpackDto::TRUE)
                    ->setIsValidator(BackpackDto::TRUE)
                    ->setVisible(BackpackDto::TRUE);
                break;
            case self::MY_ABANDONNED_UPDATABLE:
                if (!is_null($this->user)) {
                    $dto->setOwnerDto((new UserDto())->setId($this->user->getId()));
                }
                $dto
                    ->setStateCurrent(WorkflowData::STATE_ABANDONNED)
                    ->setIsContributor(BackpackDto::TRUE)
                    ->setIsValidator(BackpackDto::TRUE)
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
                    ->setIsContributor(BackpackDto::TRUE)
                    ->setVisible(BackpackDto::TRUE);
                break;
            case self::MY_TO_RESUME_UPDATABLE:
                if (!is_null($this->user)) {
                    $dto->setOwnerDto((new UserDto())->setId($this->user->getId()));
                }
                $dto
                    ->setIsContributor(BackpackDto::TRUE)
                    ->setStateCurrent(WorkflowData::STATE_TO_RESUME)
                    ->setVisible(BackpackDto::TRUE);
                break;
            case self::TO_VALIDATE:
                $dto
                    ->setStateCurrent(WorkflowData::STATE_TO_VALIDATE)
                    ->setVisible(BackpackDto::TRUE);
                break;
            case self::TO_VALIDATE_UPDATABLE:
                if (!is_null($this->user)) {
                    $dto->setUserDto((new UserDto())->setId($this->user->getId()));
                }
                $dto
                    ->setStateCurrent(WorkflowData::STATE_TO_VALIDATE)
                    ->setIsValidatorForCategory(BackpackDto::TRUE)
                    ->setVisible(BackpackDto::TRUE);
                break;
            case self::MY_TO_VALIDATE_UPDATABLE:
                if (!is_null($this->user)) {
                    $dto->setOwnerDto((new UserDto())->setId($this->user->getId()));
                }
                $dto
                    ->setIsValidatorForCategory(BackpackDto::TRUE)
                    ->setStateCurrent(WorkflowData::STATE_TO_VALIDATE)
                    ->setVisible(BackpackDto::TRUE);
                break;
            case self::TO_CONTROL:
                $dto
                    ->setStateCurrent(WorkflowData::STATE_TO_CONTROL)
                    ->setVisible(BackpackDto::TRUE);
                break;
            case self::TO_CHECK:
                $dto
                    ->setStateCurrent(WorkflowData::STATE_TO_CHECK)
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
