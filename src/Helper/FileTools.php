<?php

namespace App\Helper;

use Symfony\Component\Filesystem\Exception\IOExceptionInterface;
use Symfony\Component\HttpFoundation\File\Exception\FileNotFoundException;

/**
 * @author Emmanuel SAUVAGE <emmanuel.sauvage@live.fr>
 * @version 1.0.0
 */
class FileTools extends AbstractFsObject
{

    public function remove(string $path, string $file)
    {
        try {

            $fullpath = $this->fullPath($path, $file);

            if ($this->fsObject->exists($fullpath)) {
                $this->fsObject->remove($fullpath);
            }
        } catch (IOExceptionInterface $exception) {
            echo "Error remove file at" . $exception->getPath();
        }
    }

    public function copy(string $pathSource, string $fileSource, string $pathDestination, string $fileDestination)
    {
        try {

            $fullpathSource = $this->fullPath($pathSource, $fileSource);
            $fullpathDestination = $this->fullPath($pathDestination, $fileDestination);

            if ($this->exist($pathSource, $fileSource)) {
                $this->remove($pathDestination, $fileDestination);
                $this->fsObject->copy($fullpathSource, $fullpathDestination);
            } else {
                throw new FileNotFoundException('le fichier ' . $pathSource .'/'. $fileSource . ' n\'existe pas');
            }
        } catch (IOExceptionInterface $exception) {
            echo "Error move file at" . $exception->getPath();
        }
    }


    public function move(string $pathSource, string $fileSource, string $pathDestination, string $fileDestination)
    {
        $this->copy($pathSource, $fileSource, $pathDestination, $fileDestination);
        $this->remove($pathSource, $fileSource);
    }

    public function exist(string $path, string $file)
    {
        $fullpath = $this->fullPath($path, $file);

        return $this->fsObject->exists($fullpath);
    }


    public function size(string $path, string $file)
    {
        $fullpath = $this->fullPath($path, $file);

        if ($this->exist($path, $file)) {
            return filesize($fullpath);
        }
        return 0;
    }

    public function write(string $path, string $file, string $content,bool $append=false)
    {
        $fullFile= $path . '/' . $file;

        
        if( !$this->exist($path,$file)) {
            $this->fsObject->touch($fullFile);
        } else if(!$append) {
            $this->remove($path,$file);
        }

        $this->fsObject->appendToFile($fullFile,$content);
    }

}
