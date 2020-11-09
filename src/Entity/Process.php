<?php

namespace App\Entity;

use App\Repository\ProcessRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=ProcessRepository::class)
 */
class Process
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
    private $content;

    /**
     * @ORM\ManyToOne(targetEntity=MProcess::class, inversedBy="processes")
     * @ORM\JoinColumn(nullable=false)
     */
    private $mProcess;

    /**
     * @ORM\Column(type="string", length=5)
     */
    private $ref;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $grouping;

    /**
     * @ORM\ManyToMany(targetEntity=User::class, inversedBy="processvalidators")
     * @ORM\JoinTable("processvalidators_user")
     * @ORM\OrderBy({"name" = "ASC"})
     */
    private $validators;

    /**
     * @ORM\ManyToMany(targetEntity=User::class, inversedBy="processContributors")
     * @ORM\JoinTable("processcontributors_user")
     * @ORM\OrderBy({"name" = "ASC"})
     */
    private $contributors;

    public function __construct()
    {
        $this->validators = new ArrayCollection();
        $this->contributors = new ArrayCollection();
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
        return $this->content;
    }

    public function setContent(?string $content): self
    {
        $this->content = $content;

        return $this;
    }

    public function getMProcess(): ?MProcess
    {
        return $this->mProcess;
    }

    public function setMProcess(?MProcess $mProcess): self
    {
        $this->mProcess = $mProcess;

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

    public function getGrouping(): ?string
    {
        return $this->grouping;
    }

    public function setGrouping(?string $grouping): self
    {
        $this->grouping = $grouping;

        return $this;
    }

    /**
     * @return Collection|User[]
     */
    public function getValidators(): Collection
    {
        return $this->validators;
    }

    public function addValidator(User $validator): self
    {
        if (!$this->validators->contains($validator)) {
            $this->validators[] = $validator;
        }

        return $this;
    }

    public function removeValidator(User $validator): self
    {
        $this->validators->removeElement($validator);

        return $this;
    }

    /**
     * @return Collection|User[]
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
}
