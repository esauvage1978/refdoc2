<?php

namespace App\Listener;

use App\Entity\BackpackFileSource;
use App\Helper\DirectoryTools;
use App\Helper\FileTools;
use App\Helper\Slugger;
use App\Helper\SplitNameOfFile;
use App\Service\Uploader;
use Doctrine\ORM\Mapping as ORM;

class BackpackFileSourceUploadListener
{
    /**
     * @var Uploader
     */
    private $uploader;

    /**
     * @var string
     */
    private $directory;



    public function __construct(Uploader $uploader, string $directory)
    {
        $this->uploader = $uploader;
        $this->directory = $directory;
        
    }

    /**
     * @ORM\PrePersist
     * @ORM\PreUpdate
     */
    public function prePersistHandler(BackpackFileSource $backpackFileSource)
    {

        $file = $backpackFileSource->getFile();
        if (!empty($file)) {

            $splitNameFile = new SplitNameOfFile($file->getClientOriginalName());
            $extension = $splitNameFile->getExtension();

            if (empty($backpackFileSource->getFileName())) {
                $backpackFileSource->setFileName(Slugger::slugify($splitNameFile->getName()));
            }
            if (empty($backpackFileSource->getTitle())) {
                $backpackFileSource->setTitle('Nouveau fichier');
            }

            $backpackFileSource->setFileExtension($extension);
            $backpackFileSource->setSize($this->uploader->getSize($file));
        }
        $backpackFileSource->setUpdatedAt(new \DateTime());
    }

    /**
     * @ORM\PostPersist()
     * @ORM\PostUpdate()
     */
    public function postPersistHandler(BackpackFileSource $backpackFileSource)
    {
        if (!empty($backpackFileSource->getFile())) {
            $DirectoryTools = new DirectoryTools();
            $fileTools = new FileTools();

            $DirectoryTools->create($this->directory, $backpackFileSource->getBackpack()->getId());
            $targetDir = $this->directory . '/' . $backpackFileSource->getBackpack()->getId();

            if (null !== $backpackFileSource->getFullName()) {
                $fileTools->remove($targetDir, $backpackFileSource->getFullName());
            }

            $this->uploader->setTargetDir($targetDir);
            $this->uploader->upload($backpackFileSource->getFile(), $backpackFileSource->getFileName());
        }
    }

    /**
     * @ORM\PostRemove()
     */
    public function postRemoveHandler(BackpackFileSource $backpackFileSource)
    {
        $fileDirectory = new FileTools();
        $targetDir = $this->directory . '/' . $backpackFileSource->getBackpack()->getId();
        $fileDirectory->remove($targetDir, $backpackFileSource->getFullName());
    }
}
