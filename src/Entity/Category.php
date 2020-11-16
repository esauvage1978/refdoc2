<?php

namespace App\Entity;

use App\Repository\CategoryRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=CategoryRepository::class)
 */
class Category implements EntityInterface
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=50)
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
     * @ORM\Column(type="boolean")
     */
    private $isValidatedByControl;

    /**
     * @ORM\Column(type="boolean")
     */
    private $isValidatedByDoc;

    /**
     * @ORM\Column(type="integer")
     */
    private $timeBeforeRevision;

    /**
     * @ORM\Column(type="string", length=50)
     */
    private $icone;

    /**
     * @ORM\Column(type="string", length=7)
     */
    private $bgColor;

    /**
     * @ORM\Column(type="string", length=7)
     */
    private $foreColor;

    /**
     * @ORM\Column(type="string", length=30)
     */
    private $workflowName;

    public function __construct()
    {
        $this->setIsEnable(true);
        $this->setIsValidatedByControl(false);
        $this->setIsValidatedByDoc(true);
        $this->setTimeBeforeRevision(6);
        $this->setIcone("fas fa-book");
        $this->setBgColor("#ffffff");
        $this->setForeColor("#ff00ff");
        $this->setWorkflowName("wkf_all");
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

    public function getIsValidatedByControl(): ?bool
    {
        return $this->isValidatedByControl;
    }

    public function setIsValidatedByControl(bool $isValidatedByControl): self
    {
        $this->isValidatedByControl = $isValidatedByControl;

        return $this;
    }

    public function getIsValidatedByDoc(): ?bool
    {
        return $this->isValidatedByDoc;
    }

    public function setIsValidatedByDoc(bool $isValidatedByDoc): self
    {
        $this->isValidatedByDoc = $isValidatedByDoc;

        return $this;
    }

    public function getTimeBeforeRevision(): ?int
    {
        return $this->timeBeforeRevision;
    }

    public function setTimeBeforeRevision(int $timeBeforeRevision): self
    {
        $this->timeBeforeRevision = $timeBeforeRevision;

        return $this;
    }

    public function getIcone(): ?string
    {
        return $this->icone;
    }

    public function setIcone(string $icone): self
    {
        $this->icone = $icone;

        return $this;
    }

    public function getBgColor(): ?string
    {
        return $this->bgColor;
    }

    public function setBgColor(string $bgColor): self
    {
        $this->bgColor = $bgColor;

        return $this;
    }

    public function getForeColor(): ?string
    {
        return $this->foreColor;
    }

    public function setForeColor(string $foreColor): self
    {
        $this->foreColor = $foreColor;

        return $this;
    }

    public function getWorkflowName(): ?string
    {
        return $this->workflowName;
    }

    public function setWorkflowName(string $workflowName): self
    {
        $this->workflowName = $workflowName;

        return $this;
    }
}
