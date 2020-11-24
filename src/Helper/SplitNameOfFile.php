<?php

namespace App\Helper;

/**
 * @author Emmanuel SAUVAGE <emmanuel.sauvage@live.fr>
 * @version 1.0.0
 */
class SplitNameOfFile
{
    private $name;
    private $extension;


    public function __construct($fileName)
    {
        $this->split($fileName);
    }


    private function split($fileName)
    {
        $len = strlen($fileName);
        $explode = explode('.', $fileName);
        
        $this->extension = 
            sizeof($explode) > 1 
            ? $explode[sizeof($explode) - 1] 
            : '';

        $this->name =
            sizeof($explode) > 1
            ? substr($fileName, 0, $len - strlen($this->extension) - 1)
            : $explode[0];
    }

    /**
     * @return mixed
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * @return mixed
     */
    public function getExtension()
    {
        return strtolower($this->extension);
    }
}
