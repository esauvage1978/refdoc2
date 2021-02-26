<?php


namespace App\History;


class HistoryShow
{
    /**
     * @var string
     */
    protected $route;

    /**
     * @var string
     */
    protected $title;

    /**
     * @var string
     */
    protected $complement;

    public function __construct(
        string $route,
        string $title,
        string $complement
    ) {
        $this->route = $route;
        $this->title = $title;
        $this->complement = $complement;
    }

    public function getParams()
    {
        return [
            'route' => $this->route,
            'title' => $this->title,
            'complement'=>$this->complement
        ];
    }

}