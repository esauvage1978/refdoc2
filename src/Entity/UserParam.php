<?php

namespace App\Entity;

use App\Repository\UserParamRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
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



    /**
     * @ORM\OneToOne(targetEntity=User::class, mappedBy="userParam", cascade={"persist", "remove"})
     * @ORM\OrderBy({"name" = "ASC"})
     */
    private $user;



    public function __construct()
    {
        $this->setIsDoc(false);
        $this->setIsControl(false);
        $this->setIsSubscription(false);

    }


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





    public function getUser(): ?User
    {
        return $this->user;
    }

    public function setUser(?User $user): self
    {
        $this->user = $user;

        // set (or unset) the owning side of the relation if necessary
        $newUserParam = null === $user ? null : $this;
        if ($user->getUserParam() !== $newUserParam) {
            $user->setUserParam($newUserParam);
        }

        return $this;
    }


}
