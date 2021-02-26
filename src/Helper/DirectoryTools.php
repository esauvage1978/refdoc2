<?php

namespace App\Helper;

use Symfony\Component\Filesystem\Exception\IOExceptionInterface;
use Symfony\Component\Filesystem\Filesystem;

/**
 * @author Emmanuel SAUVAGE <emmanuel.sauvage@live.fr>
 * @version 1.0.0
 */
class DirectoryTools extends AbstractFsObject
{

    public function exist(string $path, string $directory)
    {
        $fullPath = $this->fullPath($path, $directory);

        return $this->fsObject->exists($fullPath);
    }

    public function create(string $path, string $directory)
    {

        $fullPath = $this->fullPath($path, $directory);

        if (!$this->fsObject->exists($fullPath)) {
            try {
                $old = umask(0);
                $this->fsObject->mkdir($fullPath, 0775);
                umask($old);
            } catch (\Exception $e) {
                dump($e->getMessage());
            }
        }
    }

    public function remove(string $path, string $directory)
    {
        $fullPath = $this->fullPath($path, $directory);

        return $this->fsObject->remove($fullPath);
    }

}
