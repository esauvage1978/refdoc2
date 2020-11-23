<?php

namespace App\Entity;

use App\Repository\BackpackRepository;
use App\Workflow\WorkflowData;
use App\Workflow\WorkflowNames;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;

/**
 * @ORM\Entity(repositoryClass=BackpackRepository::class)
 */
class Backpack implements EntityInterface
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
     * @ORM\Column(type="text", nullable=true)
     */
    private $content;

    /**
     * @ORM\Column(type="datetime")
     */
    private $createdAt;

    /**
     * @ORM\Column(type="datetime", nullable=true)
     */
    private $updatedAt;

    /**
     * @ORM\ManyToOne(targetEntity=User::class, inversedBy="backpacks")
     */
    private $owner;

    /**
     * @ORM\ManyToOne(targetEntity=Category::class, inversedBy="backpacks")
     * @ORM\JoinColumn(nullable=false)
     */
    private $category;

    /**
     * @ORM\Column(type="string", length=30)
     */
    private $stateCurrent;

    /**
     * @ORM\Column(type="datetime")
     */
    private $stateAt;

    /**
     * @ORM\Column(type="text", nullable=true)
     */
    private $stateContent;

    /**
     * @ORM\Column(type="string", length=100, nullable=true)
     */
    private $dir1;

    /**
     * @ORM\Column(type="string", length=100, nullable=true)
     */
    private $dir2;

    /**
     * @ORM\Column(type="string", length=100, nullable=true)
     */
    private $dir3;

    /**
     * @ORM\Column(type="string", length=100, nullable=true)
     */
    private $dir4;

    /**
     * @ORM\Column(type="string", length=100, nullable=true)
     */
    private $dir5;

    /**
     * @ORM\ManyToOne(targetEntity=MProcess::class, inversedBy="backpacks")
     * @ORM\JoinColumn(nullable=false)
     */
    private $mProcess;

    /**
     * @ORM\ManyToOne(targetEntity=Process::class, inversedBy="backpacks")
     * @ORM\JoinColumn(nullable=true)
     */
    private $process;


    /**
     * @ORM\OneToMany(targetEntity="App\Entity\BackpackFile", mappedBy="backpack", orphanRemoval=true,cascade={"persist"})
     */
    private $backpackFiles;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\BackpackLink", mappedBy="backpack",cascade={"persist"})
     */
    private $backpackLinks;

    public function __construct()
    {
        $this->setCreatedAt(new \DateTime());
        $this->setStateCurrent(WorkflowData::STATE_DRAFT);
        $this->setStateAt(new \DateTime());
        $this->backpackFiles = new ArrayCollection();
        $this->backpackLinks = new ArrayCollection();
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

    public function getContent(): ?string
    {
        return $this->content;
    }

    public function setContent(?string $content): self
    {
        $this->content = $content;

        return $this;
    }

    public function getCreatedAt(): ?\DateTimeInterface
    {
        return $this->createdAt;
    }

    public function setCreatedAt(\DateTimeInterface $createdAt): self
    {
        $this->createdAt = $createdAt;

        return $this;
    }

    public function getUpdatedAt(): ?\DateTimeInterface
    {
        return $this->updatedAt;
    }

    public function setUpdatedAt(?\DateTimeInterface $updatedAt): self
    {
        $this->updatedAt = $updatedAt;

        return $this;
    }

    public function getOwner(): ?User
    {
        return $this->owner;
    }

    public function setOwner(?User $owner): self
    {
        $this->owner = $owner;

        return $this;
    }

    public function getCategory(): ?Category
    {
        return $this->category;
    }

    public function setCategory(?Category $category): self
    {
        $this->category = $category;

        return $this;
    }

    public function getStateCurrent(): ?string
    {
        return $this->stateCurrent;
    }

    public function setStateCurrent(string $stateCurrent): self
    {
        WorkflowData::hasState($stateCurrent);

        $this->stateCurrent = $stateCurrent;

        return $this;
    }

    public function getStateAt(): ?\DateTimeInterface
    {
        return $this->stateAt;
    }

    public function setStateAt(\DateTimeInterface $stateAt): self
    {
        $this->stateAt = $stateAt;

        return $this;
    }

    public function getStateContent(): ?string
    {
        return $this->stateContent;
    }

    public function setStateContent(?string $stateContent): self
    {
        $this->stateContent = $stateContent;

        return $this;
    }

    public function getDir1(): ?string
    {
        return $this->dir1;
    }

    public function setDir1(?string $dir1): self
    {
        $this->dir1 = $dir1;

        return $this;
    }

    public function getDir2(): ?string
    {
        return $this->dir2;
    }

    public function setDir2(?string $dir2): self
    {
        $this->dir2 = $dir2;

        return $this;
    }

    public function getDir3(): ?string
    {
        return $this->dir3;
    }

    public function setDir3(?string $dir3): self
    {
        $this->dir3 = $dir3;

        return $this;
    }

    public function getDir4(): ?string
    {
        return $this->dir4;
    }

    public function setDir4(?string $dir4): self
    {
        $this->dir4 = $dir4;

        return $this;
    }

    public function getDir5(): ?string
    {
        return $this->dir5;
    }

    public function setDir5(?string $dir5): self
    {
        $this->dir5 = $dir5;

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

    public function getProcess(): ?Process
    {
        return $this->process;
    }

    public function setProcess(?Process $process): self
    {
        $this->process = $process;

        return $this;
    }


    /**
     * @return Collection|BackpackFile[]
     */
    public function getBackpackFiles(): Collection
    {
        return $this->backpackFiles;
    }

    public function addBackpackFile(BackpackFile $backpackFile): self
    {
        if (!$this->backpackFiles->contains($backpackFile)) {
            $this->backpackFiles[] = $backpackFile;
            $backpackFile->setBackpack($this);
        }

        return $this;
    }



    /**
     * @return Collection|BackpackLink[]
     */
    public function getBackpackLinks(): Collection
    {
        return $this->backpackLinks;
    }

    public function addBackpackLink(BackpackLink $backpackLink): self
    {
        if (!$this->backpackLinks->contains($backpackLink)) {
            $this->backpackLinks[] = $backpackLink;
            $backpackLink->setBackpack($this);
        }

        return $this;
    }

    public function removeBackpackLink(BackpackLink $backpackLink): self
    {
        if ($this->backpackLinks->contains($backpackLink)) {
            $this->backpackLinks->removeElement($backpackLink);
            // set the owning side to null (unless already changed)
            if ($backpackLink->getBackpack() === $this) {
                $backpackLink->setBackpack(null);
            }
        }

        return $this;
    }
    public function removeBackpackFile(BackpackFile $backpackFile): self
    {
        if ($this->backpackFiles->contains($backpackFile)) {
            $this->backpackFiles->removeElement($backpackFile);
            // set the owning side to null (unless already changed)
            if ($backpackFile->getBackpack() === $this) {
                $backpackFile->setBackpack(null);
            }
        }

        return $this;
    }
}