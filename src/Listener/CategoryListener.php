<?php

namespace App\Listener;

use App\Entity\Category;
use App\Service\CategoryCss;
use Doctrine\Persistence\Event\LifecycleEventArgs;

class CategoryListener
{
    /**
     * @var CategoryCss
     */
    private $categoryCss;

    public function __construct(
        CategoryCss $categoryCss
    ) {
        $this->categoryCss = $categoryCss;
    }

    // the entity listener methods receive two arguments:
    // the entity instance and the lifecycle event
    public function postUpdate(Category $category, LifecycleEventArgs $event)
    {
        $this->categoryCss->create();
    }
}
