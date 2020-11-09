<?php

namespace App\Entity;

use App\Repository\MProcessRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=MProcessRepository::class)
 */
class MProcess implements EntityInterface
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $name;

    /**
     * @ORM\Column(type="boolean")
     */
    private $isEnable;

    /**
     * @ORM\Column(type="text", nullable=true)
     */
    private $Content;

    /**
     * @ORM\Column(type="string", length=5)
     */
    private $ref;

    /**
     * @ORM\ManyToMany(targetEntity=User::class, inversedBy="mProcessContributors")
     * @ORM\JoinTable("mprocesscontributors_user")
     * @ORM\OrderBy({"name" = "ASC"})
     */
    private $contributors;

    /**
     * @ORM\ManyToMany(targetEntity=User::class, inversedBy="mProcessPoleValidators")
     * @ORM\JoinTable("mprocesspolevalidators_user")
     * @ORM\OrderBy({"name" = "ASC"})    
     */
    private $poleValidators;

    /**
     * @ORM\ManyToMany(targetEntity=User::class, inversedBy="mProcessDirValidators")
     * @ORM\JoinTable("mprocessdirvalidators_user")
     * @ORM\OrderBy({"name" = "ASC"})    
     */
    private $dirValidators;

    public function __construct()
    {
        $this->setIsEnable(true);
        $this->contributors = new ArrayCollection();
        $this->dirValidators = new ArrayCollection();
        $this->poleValidators = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
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

    public function getIsEnable(): ?bool
    {
        return $this->isEnable;
    }

    public function setIsEnable(bool $isEnable): self
    {
        $this->isEnable = $isEnable;

        return $this;
    }

    public function getContent(): ?string
    {
        return $this->Content;
    }

    public function setContent(?string $Content): self
    {
        $this->Content = $Content;

        return $this;
    }

    public function getRef(): ?string
    {
        return $this->ref;
    }

    public function setRef(string $ref): self
    {
        $this->ref = $ref;

        return $this;
    }

    /**
     * @return Collection|UserParam[]
     */
    public function getContributors(): Collection
    {
        return $this->contributors;
    }

    public function addContributor(User $contributor): self
    {
        if (!$this->contributors->contains($contributor)) {
            $this->contributors[] = $contributor;
        }

        return $this;
    }

    public function removeContributor(User $contributor): self
    {
        $this->contributors->removeElement($contributor);

        return $this;
    }


    /**
     * @return Collection|User[]
     */
    public function getDirValidators(): Collection
    {
        return $this->dirValidators;
    }

    public function addDirValidator(User $dirValidator): self
    {
        if (!$this->dirValidators->contains($dirValidator)) {
            $this->dirValidators[] = $dirValidator;
        }

        return $this;
    }

    public function removeDirValidator(User $dirValidator): self
    {
        if ($this->dirValidators->contains($dirValidator)) {
            $this->dirValidators->removeElement($dirValidator);
        }

        return $this;
    }

    /**
     * @return Collection|User[]
     */
    public function getPoleValidators(): Collection
    {
        return $this->poleValidators;
    }

    public function addPoleValidator(User $poleValidator): self
    {
        if (!$this->poleValidators->contains($poleValidator)) {
            $this->poleValidators[] = $poleValidator;
        }

        return $this;
    }

    public function removePoleValidator(User $poleValidator): self
    {
        if ($this->poleValidators->contains($poleValidator)) {
            $this->poleValidators->removeElement($poleValidator);
        }

        return $this;
    }
}
