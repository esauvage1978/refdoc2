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

    /**
     * @ORM\OneToMany(targetEntity=Process::class, mappedBy="mProcess")
     */
    private $processes;

    /**
     * @ORM\OneToMany(targetEntity=Subscription::class, mappedBy="mProcess", orphanRemoval=true,cascade={"persist"})
     */
    private $subscriptions;

    /**
     * @ORM\OneToMany(targetEntity=Backpack::class, mappedBy="mProcess")
     */
    private $backpacks;

    /**
     * @ORM\Column(type="integer")
     */
    private $showOrder;

    public function __construct()
    {
        $this->setIsEnable(true);
        $this->contributors = new ArrayCollection();
        $this->dirValidators = new ArrayCollection();
        $this->poleValidators = new ArrayCollection();
        $this->processes = new ArrayCollection();
        $this->subscriptions = new ArrayCollection();
        $this->backpacks = new ArrayCollection();
        $this->showOrder=0;
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

    /**
     * @return Collection|Process[]
     */
    public function getProcesses(): Collection
    {
        return $this->processes;
    }

    public function addProcess(Process $process): self
    {
        if (!$this->processes->contains($process)) {
            $this->processes[] = $process;
            $process->setMProcess($this);
        }

        return $this;
    }

    public function removeProcess(Process $process): self
    {
        if ($this->processes->removeElement($process)) {
            // set the owning side to null (unless already changed)
            if ($process->getMProcess() === $this) {
                $process->setMProcess(null);
            }
        }

        return $this;
    }

    public function getFullName(): ?string
    {
        return $this->getRef() . ' - ' . $this->getName();
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
            $subscription->setMProcess($this);
        }

        return $this;
    }

    public function removeSubscription(Subscription $subscription): self
    {
        if ($this->subscriptions->removeElement($subscription)) {
            // set the owning side to null (unless already changed)
            if ($subscription->getMProcess() === $this) {
                $subscription->setMProcess(null);
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
            $backpack->setMProcess($this);
        }

        return $this;
    }

    public function removeBackpack(Backpack $backpack): self
    {
        if ($this->backpacks->removeElement($backpack)) {
            // set the owning side to null (unless already changed)
            if ($backpack->getMProcess() === $this) {
                $backpack->setMProcess(null);
            }
        }

        return $this;
    }

    public function getShowOrder(): ?int
    {
        return $this->showOrder;
    }

    public function setShowOrder(int $showOrder): self
    {
        $this->showOrder = $showOrder;

        return $this;
    }
}
