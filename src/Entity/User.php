<?php

declare(strict_types=1);

namespace App\Entity;

use DateTime;
use DateTimeInterface;
use function array_unique;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\Collection;

use Doctrine\Common\Collections\ArrayCollection;
use Symfony\Component\Security\Core\User\UserInterface;

/**
 * @ORM\Entity(repositoryClass="App\Repository\UserRepository")
 */
class User implements UserInterface, EntityInterface
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    private $username;

    /** @ORM\Column(type="string", length=180, unique=true) */
    private $email;

    /** @ORM\Column(type="json") */
    private $roles = [];

    /**
     * @ORM\Column(type="string")
     *
     * @var string The hashed password
     */
    private $password;

    /** @ORM\Column(type="string", length=100) */
    private $name;

    /** @ORM\Column(type="boolean") */
    private $emailValidated;

    /** @ORM\Column(type="string", length=255, nullable=true) */
    private $emailValidatedToken;

    /** @ORM\Column(type="string", length=50, nullable=true) */
    private $forget_token;

    /** @ORM\Column(type="datetime", nullable=true) */
    private $loginAt;

    /** @var ?string */
    private $plainPassword;

    /** @var ?string */
    private $plainPasswordConfirmation;
    
    /** @ORM\Column(type="datetime", nullable=true) */
    private $createdAt;

    /** @ORM\Column(type="datetime", nullable=true) */
    private $modifiedAt;


    /** @ORM\Column(type="boolean") */
    private $isEnable;

    /** @ORM\Column(type="text", nullable=true) */
    private $content;

    /** @ORM\Column(type="string", length=20, nullable=true) */
    private $phone;

    /**
     * @ORM\OneToOne(targetEntity=UserParam::class, inversedBy="user", cascade={"persist", "remove"})
    */
    private $userParam;



    /**
     * @ORM\ManyToMany(targetEntity=MProcess::class, mappedBy="contributors")
     * @ORM\JoinTable("mprocesscontributors_user")
     */
    private $mProcessContributors;

    /**
     * @ORM\ManyToMany(targetEntity=MProcess::class, mappedBy="dirValidators")
     * @ORM\JoinTable("mprocessdirvalidators_user")
     */
    private $mProcessDirValidators;
 
    /**
     * @ORM\ManyToMany(targetEntity=MProcess::class, mappedBy="poleValidators")
     * @ORM\JoinTable("mprocesspolevalidators_user")
     */
    private $mProcessPoleValidators;

    /**
     * @ORM\ManyToMany(targetEntity=Process::class, mappedBy="contributors")
     * @ORM\JoinTable("processcontributors_user")
     */
    private $processContributors;

    /**
     * @ORM\ManyToMany(targetEntity=Process::class, mappedBy="validators")
     * @ORM\JoinTable("processvalidators_user")
     */
    private $processValidators;

    /**
     * @ORM\OneToMany(targetEntity=Subscription::class, mappedBy="user", orphanRemoval=true)
     */
    private $subscriptions;

    /**
     * @ORM\OneToMany(targetEntity=Backpack::class, mappedBy="owner")
     */
    private $backpacks;

    /**
     * @ORM\OneToMany(targetEntity="History", mappedBy="user", orphanRemoval=true)
     */
    private $histories;


    /**
     * @ORM\OneToMany(targetEntity="App\Entity\BackpackState", mappedBy="user")
     */
    private $backpackStates;

    public function __construct()
    {
        $this->setIsEnable(true);
        $this->setEmailValidated(false);
        $this->mProcessContributors = new ArrayCollection();
        $this->mProcessPoleValidators = new ArrayCollection();
        $this->mProcessDirValidators = new ArrayCollection();
        $this->processContributors = new ArrayCollection();
        $this->processValidators = new ArrayCollection();
        $this->subscriptions = new ArrayCollection();
        $this->backpacks = new ArrayCollection();
        $this->histories = new ArrayCollection();
        $this->backpackStates = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function setId(int $id): self
    {
        $this->id = $id;

        return $this;
    }

    public function getEmail(): ?string
    {
        return $this->email;
    }

    public function setEmail(string $email): self
    {
        $this->email = $email;

        return $this;
    }

    public function setPlainPassword(string $plainPassword): self
    {
        $this->plainPassword = $plainPassword;

        return $this;
    }

    public function getPlainPassword(): ?string
    {
        return $this->plainPassword;
    }

    public function setPlainPasswordConfirmation(string $plainPasswordConfirmation): self
    {
        $this->plainPasswordConfirmation = $plainPasswordConfirmation;

        return $this;
    }

    public function getPlainPasswordConfirmation(): ?string
    {
        return $this->plainPasswordConfirmation;
    }

    /**
     * A visual identifier that represents this user.
     *
     * @see UserInterface
     */
    public function getUsername(): string
    {
        return (string) $this->email;
    }

    /**
     * @see UserInterface
     */
    public function getRoles(): array
    {
        $roles = $this->roles;

        return array_unique($roles);
    }

    public function setRoles(array $roles): self
    {
        $this->roles = $roles;

        return $this;
    }

    /**
     * @see UserInterface
     */
    public function getPassword(): string
    {
        return (string) $this->password;
    }

    public function setPassword(string $password): self
    {
        $this->password = $password;

        return $this;
    }

    /**
     * @see UserInterface
     */
    public function getSalt(): void
    {
    }

    /**
     * @see UserInterface
     */
    public function eraseCredentials(): void
    {
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name): self
    {
        $this->name = $name;

        return $this;
    }

    public function getEmailValidated(): ?bool
    {
        return $this->emailValidated;
    }

    public function setEmailValidated(bool $emailValidated): self
    {
        $this->emailValidated = $emailValidated;

        return $this;
    }

    public function getEmailValidatedToken(): ?string
    {
        return $this->emailValidatedToken;
    }

    public function setEmailValidatedToken(?string $emailValidatedToken): self
    {
        $this->emailValidatedToken = $emailValidatedToken;

        return $this;
    }

    public function getLoginAt(): ?DateTimeInterface
    {
        return $this->loginAt;
    }

    public function setLoginAt(?DateTimeInterface $loginAt): self
    {
        $this->loginAt = $loginAt;

        return $this;
    }

    public function getCreatedAt(): ?DateTime
    {
        return $this->createdAt;
    }

    public function setCreatedAt(?DateTimeInterface $createdAt): self
    {
        $this->createdAt = $createdAt;

        return $this;
    }

    public function getModifiedAt(): ?DateTimeInterface
    {
        return $this->modifiedAt;
    }

    public function setModifiedAt(?DateTimeInterface $modifiedAt): self
    {
        $this->modifiedAt = $modifiedAt;

        return $this;
    }

    public function getIsEnable(): ?bool
    {
        return $this->isEnable;
    }

    public function setIsEnable(bool $isEnable): self
    {
        $this->isEnable = $isEnable;

        return $this;
    }

    public function getForgetToken(): ?string
    {
        return $this->forget_token;
    }

    public function setForgetToken(?string $forget_token): self
    {
        $this->forget_token = $forget_token;

        return $this;
    }

    public function getContent(): ?string
    {
        return $this->content;
    }

    public function setContent(?string $content): self
    {
        $this->content = $content;

        return $this;
    }

    public function getPhone(): ?string
    {
        return $this->phone;
    }

    public function setPhone(?string $phone): self
    {
        $this->phone = $phone;

        return $this;
    }

    public function getAvatar(): string
    {
        return 'avatar/' . $this->getId() . '.png';
    }

    public function getUserParam(): ?UserParam
    {
        return $this->userParam;
    }

    public function setUserParam(?UserParam $userParam): self
    {
        $this->userParam = $userParam;

        return $this;
    }

    /**
     * @return Collection|Process[]
     */
    public function getProcessContributors(): Collection
    {
        return $this->processContributors;
    }

    public function addProcessContributor(Process $processContributor): self
    {
        if (!$this->processContributors->contains($processContributor)) {
            $this->processContributors[] = $processContributor;
            $processContributor->addContributor($this);
        }

        return $this;
    }

    public function removeProcessContributor(Process $processContributor): self
    {
        if ($this->processContributors->contains($processContributor)) {
            $this->processContributors->removeElement($processContributor);
            $processContributor->removeContributor($this);
        }

        return $this;
    }

    /**
     * @return Collection|Process[]
     */
    public function getProcessValidators(): Collection
    {
        return $this->processValidators;
    }

    public function addProcessPoleValidator(Process $processValidators): self
    {
        if (!$this->processValidators->contains($processValidators)) {
            $this->processValidators[] = $processValidators;
            $processValidators->addValidator($this);
        }

        return $this;
    }

    public function removeProcessPoleValidator(Process $processValidators): self
    {
        if ($this->processValidators->contains($processValidators)) {
            $this->processValidators->removeElement($processValidators);
            $processValidators->removeValidator($this);
        }

        return $this;
    }

    /**
     * @return Collection|MProcess[]
     */
    public function getMProcessContributors(): Collection
    {
        return $this->mProcessContributors;
    }

    public function addMProcessContributor(MProcess $mProcessContributor): self
    {
        if (!$this->mProcessContributors->contains($mProcessContributor)) {
            $this->mProcessContributors[] = $mProcessContributor;
            $mProcessContributor->addContributor($this);
        }

        return $this;
    }

    public function removeMProcessContributor(MProcess $mProcessContributor): self
    {
        if ($this->mProcessContributors->contains($mProcessContributor)) {
            $this->mProcessContributors->removeElement($mProcessContributor);
            $mProcessContributor->removeContributor($this);
        }

        return $this;
    }

    /**
     * @return Collection|MProcess[]
     */
    public function getMProcessPoleValidators(): Collection
    {
        return $this->mProcessPoleValidators;
    }

    public function addMProcessPoleValidator(MProcess $mProcessPoleValidator): self
    {
        if (!$this->mProcessPoleValidators->contains($mProcessPoleValidator)) {
            $this->mProcessPoleValidators[] = $mProcessPoleValidator;
            $mProcessPoleValidator->addPoleValidator($this);
        }

        return $this;
    }

    public function removeMProcessPoleValidator(MProcess $mProcessPoleValidator): self
    {
        if ($this->mProcessPoleValidators->contains($mProcessPoleValidator)) {
            $this->mProcessPoleValidators->removeElement($mProcessPoleValidator);
            $mProcessPoleValidator->removePoleValidator($this);
        }

        return $this;
    }

    /**
     * @return Collection|MProcess[]
     */
    public function getMProcessDirValidators(): Collection
    {
        return $this->mProcessDirValidators;
    }

    public function addMProcessDirValidator(MProcess $mProcessDirValidator): self
    {
        if (!$this->mProcessDirValidators->contains($mProcessDirValidator)) {
            $this->mProcessDirValidators[] = $mProcessDirValidator;
            $mProcessDirValidator->addDirValidator($this);
        }

        return $this;
    }

    public function removeMProcessDirValidator(MProcess $mProcessDirValidator): self
    {
        if ($this->mProcessDirValidators->contains($mProcessDirValidator)) {
            $this->mProcessDirValidators->removeElement($mProcessDirValidator);
            $mProcessDirValidator->removeDirValidator($this);
        }

        return $this;
    }

    /**
     * @return Collection|Subscription[]
     */
    public function getSubscriptions(): Collection
    {
        return $this->subscriptions;
    }

    public function addSubscription(Subscription $subscription): self
    {
        if (!$this->subscriptions->contains($subscription)) {
            $this->subscriptions[] = $subscription;
            $subscription->setUser($this);
        }

        return $this;
    }

    public function removeSubscription(Subscription $subscription): self
    {
        if ($this->subscriptions->removeElement($subscription)) {
            // set the owning side to null (unless already changed)
            if ($subscription->getUser() === $this) {
                $subscription->setUser(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection|Backpack[]
     */
    public function getBackpacks(): Collection
    {
        return $this->backpacks;
    }

    public function addBackpack(Backpack $backpack): self
    {
        if (!$this->backpacks->contains($backpack)) {
            $this->backpacks[] = $backpack;
            $backpack->setOwner($this);
        }

        return $this;
    }

    public function removeBackpack(Backpack $backpack): self
    {
        if ($this->backpacks->removeElement($backpack)) {
            // set the owning side to null (unless already changed)
            if ($backpack->getOwner() === $this) {
                $backpack->setOwner(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection|History[]
     */
    public function getHistories(): Collection
    {
        return $this->histories;
    }

    public function addHistory(History $history): self
    {
        if (!$this->histories->contains($history)) {
            $this->histories[] = $history;
            $history->setUser($this);
        }

        return $this;
    }

    public function removeHistory(History $history): self
    {
        if ($this->histories->contains($history)) {
            $this->histories->removeElement($history);
            // set the owning side to null (unless already changed)
            if ($history->getUser() === $this) {
                $history->setUser(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection|backpackState[]
     */
    public function getbackpackStates(): Collection
    {
        return $this->backpackStates;
    }

    public function addbackpackState(backpackState $backpackState): self
    {
        if (!$this->backpackStates->contains($backpackState)) {
            $this->backpackStates[] = $backpackState;
            $backpackState->setUser($this);
        }

        return $this;
    }

    public function removebackpackState(backpackState $backpackState): self
    {
        if ($this->backpackStates->contains($backpackState)) {
            $this->backpackStates->removeElement($backpackState);
            // set the owning side to null (unless already changed)
            if ($backpackState->getUser() === $this) {
                $backpackState->setUser(null);
            }
        }

        return $this;
    }
 
}
