<?php

namespace App\Dto;

use App\Workflow\WorkflowData;
use Symfony\Component\HttpFoundation\Request;

class BackpackDto extends AbstractDto
{

    /**
     * @var ?MProcessDto
     */
    private $mProcessDto;

    /**
     * @var ?processDto
     */
    private $processDto;


    /**
     * @var ?UserDto
     */
    private $userDto;

    /**
     * @var ?UserDto
     */
    private $ownerDto;

    /**
     * @var ?string
     */
    private $stateCurrent;

    /**
     * @var ?string
     */
    private $stateInProgress;

    /**
     * @var ?string
     */
    private $isNew;

    /**
     * @var ?string
     */
    private $isControl;

    /**
     * @var ?string
     */
    private $isContributor;

    /**
     * @var ?string
     */
    private $isValidator;

    /**
     * @var ?string
     */
    private $isValidatorForCategory;


    /**
     * @var ?string
     */
    private $isForSubscription;

    /**
     * @return mixed
     */
    public function getMProcessDto()
    {
        return $this->mProcessDto;
    }

    /**
     * @param mixed $mProcessDto
     */
    public function setMProcessDto($mProcessDto)
    {
        $this->mProcessDto = $mProcessDto;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getProcessDto()
    {
        return $this->processDto;
    }

    /**
     * @param mixed $processDto
     */
    public function setProcessDto($processDto)
    {
        $this->processDto = $processDto;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getUserDto()
    {
        return $this->userDto;
    }

    /**
     * @param mixed $userDto
     */
    public function setUserDto($userDto)
    {
        $this->userDto = $userDto;
        return $this;
    }


    /**
     * @return mixed
     */
    public function getOwnerDto()
    {
        return $this->ownerDto;
    }

    /**
     * @param mixed $ownerDto
     * @return BackpackDto
     */
    public function setOwnerDto($ownerDto)
    {
        $this->ownerDto = $ownerDto;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getStateCurrent()
    {
        return $this->stateCurrent;
    }

    /**
     * @param mixed $stateCurrent
     * @return BackpackDto
     */
    public function setStateCurrent($stateCurrent)
    {
        $this->stateCurrent = $stateCurrent;
        return $this;
    }


    /**
     * @param mixed $stateInProgress
     * @return BackpackDto
     */
    public function setStatInProgress($stateInProgress)
    {
        $this->checkBool($stateInProgress);
        $this->stateInProgress = $stateInProgress;
        return $this;
    }


    /**
     * @return mixed
     */
    public function getStateInProgressData()
    {
        return
        [
            WorkflowData::STATE_DRAFT,
            WorkflowData::STATE_TO_RESUME,
            WorkflowData::STATE_TO_VALIDATE,
            WorkflowData::STATE_TO_CHECK,
            WorkflowData::STATE_TO_CONTROL,
        ];
    }

    /**
     * @return mixed
     */
    public function getStateInProgress()
    {
        return $this->stateInProgress;
    }

    /**
     * @param mixed $isNew
     * @return BackpackDto
     */
    public function setIsNew($isNew)
    {
        $this->checkBool($isNew);
        $this->isNew = $isNew;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getIsNew()
    {
        return $this->isNew;
    }

    /**
     * @param mixed $isControl
     * @return BackpackDto
     */
    public function setIsControl($isControl)
    {
        $this->checkBool($isControl);
        $this->isControl = $isControl;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getIsControl()
    {
        return $this->isControl;
    }
    /**
     * @return mixed
     */
    public function getIsContributor()
    {
        return $this->isContributor;
    }

    /**
     * @param mixed $isNew
     * @return BackpackDto
     */
    public function setIsContributor($isContributor)
    {
        $this->checkBool($isContributor);
        $this->isContributor = $isContributor;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getIsValidator()
    {
        return $this->isValidator;
    }

    /**
     * @param mixed $isNew
     * @return BackpackDto
     */
    public function setIsValidator($isValidator)
    {
        $this->checkBool($isValidator);
        $this->isValidator = $isValidator;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getIsValidatorForCategory()
    {
        return $this->isValidatorForCategory;
    }

    /**
     * @param mixed $isNew
     * @return BackpackDto
     */
    public function setIsValidatorForCategory($isValidatorForCategory)
    {
        $this->checkBool($isValidatorForCategory);
        $this->isValidatorForCategory = $isValidatorForCategory;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getIsForSubscription()
    {
        return $this->isForSubscription;
    }

    /**
     * @param mixed $isForSubscription
     * @return BackpackDto
     */
    public function setIsForSubscription($isForSubscription)
    {
        $this->checkBool($isForSubscription);
        $this->isForSubscription = $isForSubscription;
        return $this;
    }

    public function getData(): array
    {
        $d = [];
        isset($this->wordSearch) && $d = array_merge($d, ['wordSearch' => $this->wordSearch]);
        isset($this->visible) && $d = array_merge($d, ['isForSubscription' => $this->isForSubscription]);
        isset($this->visible) && $d = array_merge($d, ['isNew' => $this->isNew]);
        isset($this->visible) && $d = array_merge($d, ['isContributor' => $this->isContributor]);
        isset($this->visible) && $d = array_merge($d, ['isValidator' => $this->isValidator]);
        isset($this->visible) && $d = array_merge($d, ['isValidatorForCategory' => $this->isValidatorForCategory]);
        isset($this->visible) && $d = array_merge($d, ['visible' => $this->visible]);
        isset($this->visible) && $d = array_merge($d, ['stateCurrent' => $this->stateCurrent]);
        isset($this->hide) && $d = array_merge($d, ['hide' => $this->hide]);
        isset($this->ownerDto) && isset($this->ownerDto->id) && $d = array_merge($d, ['owner' => $this->ownerDto->id]);
        isset($this->userDto) && isset($this->userDto->id) && $d = array_merge($d, ['user' => $this->userDto->id]);
        return $d;
    }
    public function setData(Request $datas)
    {
        null !== $datas->get('wordSearch') && $this->wordSearch = $datas->get('wordSearch');
        null !== $datas->get('isForSubscription') && $this->isForSubscription = $datas->get('isForSubscription');
        null !== $datas->get('isNew') && $this->isNew = $datas->get('isNew');
        null !== $datas->get('isContributor') && $this->isContributor = $datas->get('isContributor');
        null !== $datas->get('isValidator') && $this->isUisValidatordatable = $datas->get('isValidator');
        null !== $datas->get('isValidatorForCategory') && $this->isUpdatable = $datas->get('isValidatorForCategory');
        null !== $datas->get('visible') && $this->visible = $datas->get('visible');
        null !== $datas->get('hide') && $this->hide = $datas->get('hide');
        null !== $datas->get('owner') && $this->ownerDto = (new UserDto())->setId($datas->get('owner'));
        null !== $datas->get('user') && $this->userDto = (new UserDto())->setId($datas->get('user'));
        null !== $datas->get('stateCurrent') && $this->stateCurrent = $datas->get('stateCurrent');
    }

}
