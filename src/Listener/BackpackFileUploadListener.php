<?php

namespace App\Listener;

use App\Entity\BackpackFile;
use App\Helper\DirectoryTools;
use App\Helper\FileTools;
use App\Helper\SplitNameOfFile;
use App\Service\Uploader;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\String\Slugger\SluggerInterface;

class BackpackFileUploadListener
{
    /**
     * @var Uploader
     */
    private $uploader;

    /**
     * @var string
     */
    private $directory;

    /**
     * @var SluggerInterface
     */
    private $sluggerInterface;

    public function __construct(
        Uploader $uploader,
        string $directory,
        SluggerInterface $sluggerInterface )
    {
        $this->uploader = $uploader;
        $this->directory = $directory;
        $this->sluggerInterface=$sluggerInterface;
    }

    /**
     * @ORM\PrePersist
     * @ORM\PreUpdate
     */
    public function prePersistHandler(BackpackFile $backpackFile)
    {

        $file = $backpackFile->getFile();
        if (!empty($file)) {

            $splitNameFile = new SplitNameOfFile($file->getClientOriginalName());
            $extension = $splitNameFile->getExtension();

            if (empty($backpackFile->getFileName())) {
                $backpackFile->setFileName($this->sluggerInterface->slug($splitNameFile->getName()));
            }
            if (empty($backpackFile->getTitle())) {
                $backpackFile->setTitle('Nouveau fichier');
            }

            $backpackFile->setFileExtension($extension);
            $backpackFile->setSize($this->uploader->getSize($file));
        }
        $backpackFile->setUpdatedAt(new \DateTime());
    }

    /**
     * @ORM\PostPersist()
     * @ORM\PostUpdate()
     */
    public function postPersistHandler(BackpackFile $backpackFile)
    {
        if (!empty($backpackFile->getFile())) {
            $DirectoryTools = new DirectoryTools();
            $fileTools = new FileTools();

            $DirectoryTools->create($this->directory, $backpackFile->getBackpack()->getId());
            $targetDir = $this->directory . '/' . $backpackFile->getBackpack()->getId();

            if (null !== $backpackFile->getFullName()) {
                $fileTools->remove($targetDir, $backpackFile->getFullName());
            }

            $this->uploader->setTargetDir($targetDir);
            $this->uploader->upload($backpackFile->getFile(), $backpackFile->getFileName());
        }
    }

    /**
     * @ORM\PostRemove()
     */
    public function postRemoveHandler(BackpackFile $backpackFile)
    {
        $fileDirectory = new FileTools();
        $targetDir = $this->directory . '/' . $backpackFile->getBackpack()->getId();
        $fileDirectory->remove($targetDir, $backpackFile->getFullName());
    }
}
