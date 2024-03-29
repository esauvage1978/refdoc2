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
    const HOME_NEWS_SUBSCRIPTION = 'news_subscription';
    const HOME_NEWS = 'news';

    public const BACKPACK_IN_PROGRESS = 'backpack_in_progress';
    public const BACKPACK_YOURS = 'backpack_yours';

    public const SEARCH = 'search';

    public const DRAFT = 'draft';
    public const MY_DRAFT_UPDATABLE = 'mydraft_updatable';
    public const DRAFT_UPDATABLE = 'draft_updatable';

    public const ABANDONNED = 'abandonned';
    public const ABANDONNED_UPDATABLE = 'abandonned_updatable';
    public const MY_ABANDONNED_UPDATABLE = 'myabandonned_updatable';

    public const ARCHIVED = 'archived';
    public const ARCHIVED_UPDATABLE = 'archived_updatable';
    public const MY_ARCHIVED_UPDATABLE = 'archived_updatable';

    public const TO_RESUME = 'toResume';
    public const TO_RESUME_UPDATABLE = 'toResume_updatable';
    public const MY_TO_RESUME_UPDATABLE = 'mytoResume_updatable';

    public const TO_VALIDATE = 'toValidate';
    public const TO_VALIDATE_UPDATABLE = 'toValidate_updatable';
    public const MY_TO_VALIDATE_UPDATABLE = 'mytoValidate_updatable';

    public const PUBLISHED = 'published';
    public const PUBLISHED_UPDATABLE = 'published_updatable';
    public const MY_PUBLISHED_UPDATABLE = 'mypublished_updatable';

    public const GO_TO_REVISE = 'goToRevise';
    public const GO_TO_REVISE_SOON = 'goToReviseSoon';

    public const TO_REVISE = 'toRevise';
    public const TO_REVISE_UPDATABLE = 'toRevise_updatable';
    public const MY_TO_REVISE_UPDATABLE = 'mytoRevise_updatable';

    public const IN_REVIEW = 'inReview';
    public const IN_REVIEW_UPDATABLE = 'inReview_updatable';
    public const MY_IN_REVIEW_UPDATABLE = 'myinReview_updatable';

    public const TO_CONTROL = 'toControl';

    public const TO_CHECK = 'toCheck';

    public const BACKPACK_SHOW = 'show';

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
                    ->setIsInProgress(BackpackDto::TRUE)
                    ->setVisible(BackpackDto::TRUE);
                break;
            case self::BACKPACK_YOURS:
                if (!is_null($this->user)) {
                    $dto->setOwnerDto((new UserDto())->setId($this->user->getId()));
                }

                break;
            case self::HOME_NEWS_SUBSCRIPTION:
                if (!is_null($this->user)) {
                    $dto->setUserDto((new UserDto())->setId($this->user->getId()));
                }
                $dto
                    ->setIsShow(BackpackDto::TRUE)
                    ->setIsForSubscription(BackpackDto::TRUE)
                    ->setIsNew(BackpackDto::TRUE)
                    ->setVisible(BackpackDto::TRUE);
                break;
            case self::HOME_NEWS:
                if (!is_null($this->user)) {
                    $dto->setUserDto((new UserDto())->setId($this->user->getId()));
                }
                $dto
                    ->setIsShow(BackpackDto::TRUE)
                    ->setIsNew(BackpackDto::TRUE)
                    ->setVisible(BackpackDto::TRUE);
                break;
            case self::HOME_SUBSCRIPTION:
                if (!is_null($this->user)) {
                    $dto->setUserDto((new UserDto())->setId($this->user->getId()));
                }
                $dto
                    ->setIsShow(BackpackDto::TRUE)
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
            case self::ARCHIVED:
                $dto
                    ->setStateCurrent(WorkflowData::STATE_ARCHIVED)
                    ->setVisible(BackpackDto::TRUE);
                break;
            case self::ARCHIVED_UPDATABLE:
                if (!is_null($this->user)) {
                    $dto->setUserDto((new UserDto())->setId($this->user->getId()));
                }
                $dto
                    ->setStateCurrent(WorkflowData::STATE_ARCHIVED)
                    ->setIsContributor(BackpackDto::TRUE)
                    ->setIsValidator(BackpackDto::TRUE)
                    ->setVisible(BackpackDto::TRUE);
                break;
            case self::MY_ARCHIVED_UPDATABLE:
                if (!is_null($this->user)) {
                    $dto->setOwnerDto((new UserDto())->setId($this->user->getId()));
                }
                $dto
                    ->setStateCurrent(WorkflowData::STATE_ARCHIVED)
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
            case self::BACKPACK_SHOW:
                $dto
                    ->setIsShow(BackpackDto::TRUE)
                    ->setVisible(BackpackDto::TRUE);
                break;
            case self::PUBLISHED:
                $dto
                    ->setStateCurrent(WorkflowData::STATE_PUBLISHED)
                    ->setVisible(BackpackDto::TRUE);
                break;
            case self::GO_TO_REVISE:
                $dto
                    ->setStateCurrent(WorkflowData::STATE_PUBLISHED)
                    ->setIsGoToRevise(BackpackDto::TRUE)
                    ->setVisible(BackpackDto::TRUE);
                break;
            case self::GO_TO_REVISE_SOON:
                $dto
                    ->setStateCurrent(WorkflowData::STATE_PUBLISHED)
                    ->setIsGoToReviseSoon(BackpackDto::TRUE)
                    ->setVisible(BackpackDto::TRUE);
                break;
            case self::PUBLISHED_UPDATABLE:
                if (!is_null($this->user)) {
                    $dto->setUserDto((new UserDto())->setId($this->user->getId()));
                }
                $dto
                    ->setStateCurrent(WorkflowData::STATE_PUBLISHED)
                    ->setIsValidatorForCategory(BackpackDto::TRUE)
                    ->setIsContributor(BackpackDto::TRUE)
                    ->setVisible(BackpackDto::TRUE);
                break;
            case self::MY_PUBLISHED_UPDATABLE:
                if (!is_null($this->user)) {
                    $dto->setOwnerDto((new UserDto())->setId($this->user->getId()));
                }
                $dto
                    ->setStateCurrent(WorkflowData::STATE_PUBLISHED)
                    ->setIsContributor(BackpackDto::TRUE)
                    ->setVisible(BackpackDto::TRUE);
                break;
            case self::TO_REVISE:
                $dto
                    ->setStateCurrent(WorkflowData::STATE_TO_REVISE)
                    ->setVisible(BackpackDto::TRUE);
                break;
            case self::TO_REVISE_UPDATABLE:
                if (!is_null($this->user)) {
                    $dto->setUserDto((new UserDto())->setId($this->user->getId()));
                }
                $dto
                    ->setStateCurrent(WorkflowData::STATE_TO_REVISE)
                    ->setIsContributor(BackpackDto::TRUE)
                    ->setIsValidator(BackpackDto::TRUE)
                    ->setVisible(BackpackDto::TRUE);
                break;
            case self::MY_TO_REVISE_UPDATABLE:
                if (!is_null($this->user)) {
                    $dto->setOwnerDto((new UserDto())->setId($this->user->getId()));
                }
                $dto
                    ->setStateCurrent(WorkflowData::STATE_TO_REVISE)
                    ->setIsContributor(BackpackDto::TRUE)
                    ->setVisible(BackpackDto::TRUE);
                break;
            case self::IN_REVIEW:
                $dto
                    ->setStateCurrent(WorkflowData::STATE_IN_REVIEW)
                    ->setVisible(BackpackDto::TRUE);
                break;
            case self::IN_REVIEW_UPDATABLE:
                if (!is_null($this->user)) {
                    $dto->setUserDto((new UserDto())->setId($this->user->getId()));
                }
                $dto
                    ->setStateCurrent(WorkflowData::STATE_IN_REVIEW)
                    ->setIsContributor(BackpackDto::TRUE)
                    ->setIsValidator(BackpackDto::TRUE)
                    ->setVisible(BackpackDto::TRUE);
                break;
            case self::MY_IN_REVIEW_UPDATABLE:
                if (!is_null($this->user)) {
                    $dto->setOwnerDto((new UserDto())->setId($this->user->getId()));
                }
                $dto
                    ->setStateCurrent(WorkflowData::STATE_IN_REVIEW)
                    ->setIsContributor(BackpackDto::TRUE)
                    ->setVisible(BackpackDto::TRUE);
                break;
            case self::SEARCH:
                if (null === $param) {
                    throw new \InvalidArgumentException('Il manque le critère de recherche');
                }
                $dto
                    ->setSearch($param)
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
