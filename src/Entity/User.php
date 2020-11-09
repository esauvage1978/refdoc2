<?php

declare(strict_types=1);

namespace App\Entity;

use DateTime;
use DateTimeInterface;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Symfony\Component\Security\Core\User\UserInterface;

use function array_unique;

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


    public function __construct()
    {
        $this->setIsEnable(true);
        $this->setEmailValidated(false);
        $this->mProcessContributors = new ArrayCollection();
        $this->mProcessPoleValidators = new ArrayCollection();
        $this->mProcessDirValidators = new ArrayCollection();
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

 
}
