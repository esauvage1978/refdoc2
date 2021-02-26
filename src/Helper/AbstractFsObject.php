<?php

namespace App\Helper;

use Symfony\Component\Filesystem\Exception\IOExceptionInterface;
use Symfony\Component\Filesystem\Filesystem;

/**
 * @author Emmanuel SAUVAGE <emmanuel.sauvage@live.fr>
 * @version 1.0.0
 */
abstract class AbstractFsObject
{
    /**
     * @var Filesystem
     */
    protected $fsObject;


    public function __construct()
    {
        $this->fsObject = new Filesystem();
    }

    protected function fullPath(string $path, string $complement)
    {
        return $path . DIRECTORY_SEPARATOR . $complement;
    }

}