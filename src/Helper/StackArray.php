<?php


namespace App\Helper;

/**
 * @author Emmanuel SAUVAGE <emmanuel.sauvage@live.fr>
 * @version 1.0.0
 */
class StackArray
{

    /**
     * @var array
     */
    private $messages;

    public function toArray(): array
    {
        return $this->messages;
    }

    public function push(array $info)
    {
        $this->messages = array_merge(
            $this->messages,
            [$info]
        );
    }

    public function pushs(array $infos)
    {
        foreach ($infos as $info) {
            $this->push($info);
        }
    }

    public function clear()
    {
        $this->messages = [];
    }

    public function __construct()
    {
        $this->clear();
    }
}