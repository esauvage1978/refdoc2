<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\HttpFoundation\File\UploadedFile;

/**
 * @ORM\Entity(repositoryClass="App\Repository\BackpackFileRepository")
 * @ORM\EntityListeners({"App\Listener\BackpackFileUploadListener"})
 */
class BackpackFile implements EntityInterface
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;
 
    /**
     * @ORM\Column(type="string", length=255)
     */
    private $fileName;

    private $file;

    /**
     * @ORM\Column(type="datetime", nullable=true)
     */
    private $updatedAt;

    /**
     * @ORM\Column(type="string", length=10)
     */
    private $fileExtension;

    /**
     * @ORM\Column(type="integer")
     */
    private $nbrView;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Backpack", inversedBy="backpackFiles")
     * @ORM\JoinColumn(nullable=false)
     */
    private $backpack;

    /**
     * @ORM\Column(type="string", length=50)
     */
    private $title;

    /**
     * @ORM\Column(type="text", nullable=true)
     */
    private $content;

    /**
     * @ORM\Column(type="datetime", nullable=true)
     */
    private $modifyAt;

    /**
     * @ORM\Column(type="string", length=50)
     */
    private $size;

    public function __construct()
    {
        $this->setNbrView(0);
        $this->setModifyAt(new \DateTime());
    }


    /**
     * @return string
     */
    public function getFullName(): ?string
    {
        return $this->fileName . '.' . $this->fileExtension;
    }

    /**
     * @return string
     */
    public function getUploadDir(): string
    {
        return 'uploads/backpacks/' . $this->getBackpack()->getId();
    }

    public function getHref(): string
    {
        return $this->getUploadDir() .  '/' . $this->getFileName() . '.' .  $this->getFileExtension();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getFileName(): ?string
    {
        return $this->fileName;
    }

    public function setFileName(string $fileName): self
    {
        $this->fileName = $fileName;

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

    public function getFileExtension(): ?string
    {
        return $this->fileExtension;
    }

    public function setFileExtension(string $fileExtension): self
    {
        $this->fileExtension = $fileExtension;

        return $this;
    }

    public function getNbrView(): ?int
    {
        return $this->nbrView;
    }

    public function setNbrView(int $nbrView): self
    {
        $this->nbrView = $nbrView;

        return $this;
    }

    public function getBackpack(): ?Backpack
    {
        return $this->backpack;
    }

    public function setBackpack(?Backpack $backpack): self
    {
        $this->backpack = $backpack;

        return $this;
    }

    public function getTitle(): ?string
    {
        return $this->title;
    }

    public function setTitle(string $title): self
    {
        $this->title = $title;

        return $this;
    }

    public function getFile()
    {
        return $this->file;
    }

    public function setFile(UploadedFile $backpacksFile): BackpackFile
    {
        $this->file = $backpacksFile;

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

    public function getModifyAt(): ?\DateTimeInterface
    {
        return $this->modifyAt;
    }

    public function setModifyAt(?\DateTimeInterface $modifyAt): self
    {
        $this->modifyAt = $modifyAt;

        return $this;
    }

    public function getSize(): ?string
    {
        return $this->size;
    }

    public function setSize(string $size): self
    {
        $this->size = $size;

        return $this;
    }
}
