<?php

namespace App\Entity;

use App\Repository\UserParamRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=UserParamRepository::class)
 */
class UserParam
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="boolean")
     */
    private $isSubscription;

    /**
     * @ORM\Column(type="boolean")
     */
    private $isDoc;

    /**
     * @ORM\Column(type="boolean")
     */
    private $isControl;

    public function getId(): ?int
    {
        return $this->id;
    }


    public function getIsSubscription(): ?bool
    {
        return $this->isSubscription;
    }

    public function setIsSubscription(bool $isSubscription): self
    {
        $this->isSubscription = $isSubscription;

        return $this;
    }

    public function getIsDoc(): ?bool
    {
        return $this->isDoc;
    }

    public function setIsDoc(bool $isDoc): self
    {
        $this->isDoc = $isDoc;

        return $this;
    }

    public function getIsControl(): ?bool
    {
        return $this->isControl;
    }

    public function setIsControl(bool $isControl): self
    {
        $this->isControl = $isControl;

        return $this;
    }

    public function __construct()
    {
        $this->setIsDoc(false);
        $this->setIsControl(false);
        $this->setIsSubscription(false);
    }
}
