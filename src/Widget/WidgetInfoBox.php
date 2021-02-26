<?php

namespace App\Widget;


class WidgetInfoBox
{
    private $route;
    private $routeOptions;
    private $bgColor;
    private $foreColor;
    private $icon;
    private $title;
    private $data;

    /**
     * @param mixed $route
     * @return WidgetInfoBox
     */
    public function setRoute($route)
    {
        $this->route = $route;
        return $this;
    }

    /**
     * @param mixed $routeOptions
     * @return WidgetInfoBox
     */
    public function setRouteOptions($routeOptions)
    {
        $this->routeOptions = $routeOptions;
        return $this;
    }

    /**
     * @param mixed $bgColor
     * @return WidgetInfoBox
     */
    public function setBgColor($bgColor)
    {
        $this->bgColor = $bgColor;
        return $this;
    }

    /**
     * @param mixed $foreColor
     * @return WidgetInfoBox
     */
    public function setForeColor($foreColor)
    {
        $this->foreColor = $foreColor;
        return $this;
    }

    /**
     * @param mixed $icon
     * @return WidgetInfoBox
     */
    public function setIcone($icon)
    {
        $this->icon = $icon;
        return $this;
    }

    /**
     * @param mixed $title
     * @return WidgetInfoBox
     */
    public function setTitle($title)
    {
        $this->title = $title;
        return $this;
    }

    /**
     * @param mixed $data
     * @return WidgetInfoBox
     */
    public function setData($data)
    {
        $this->data = $data;
        return $this;
    }

    public function createArray()
    {
        return
            [
                'route' => $this->route,
                'routeOptions' => isset($this->routeOptions) ? $this->routeOptions : [],
                'bgColor' => isset($this->bgColor) ? $this->bgColor : 'success',
                'foreColor' => isset($this->foreColor) ? $this->foreColor : 'success',
                'icon' => isset($this->icon) ? $this->icon : 'fa fa-home',
                'title' => isset($this->title) ? $this->title : '',
                'data' => isset($this->data) ? $this->data : '-'
            ];
    }
}
