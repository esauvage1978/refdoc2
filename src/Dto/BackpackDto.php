<?php

namespace App\Dto;


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
    private $isNew;

    /**
     * @var ?string
     */
    private $isUpdatable;

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
     * @return mixed
     */
    public function getIsNew()
    {
        return $this->isNew;
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
    public function getIsUpdatable()
    {
        return $this->isUpdatable;
    }

    /**
     * @param mixed $isNew
     * @return BackpackDto
     */
    public function setIsUpdatable($isUpdatable)
    {
        $this->checkBool($isUpdatable);
        $this->isUpdatable = $isUpdatable;
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
        isset($this->visible) && $d = array_merge($d, ['isUpdatable' => $this->isUpdatable]);
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
        null !== $datas->get('isUpdatable') && $this->isUpdatable = $datas->get('isUpdatable');
        null !== $datas->get('visible') && $this->visible = $datas->get('visible');
        null !== $datas->get('hide') && $this->hide = $datas->get('hide');
        null !== $datas->get('owner') && $this->ownerDto = (new UserDto())->setId($datas->get('owner'));
        null !== $datas->get('user') && $this->userDto = (new UserDto())->setId($datas->get('user'));
        null !== $datas->get('stateCurrent') && $this->stateCurrent = $datas->get('stateCurrent');
    }

}
