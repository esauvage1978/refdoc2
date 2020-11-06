<?php

declare(strict_types=1);

namespace App\EventSubscriber;

use KevinPapst\AdminLTEBundle\Event\KnpMenuEvent;
use Knp\Menu\ItemInterface;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;

class KnpMenuBuilderSubscriber implements EventSubscriberInterface
{

    /** @var ItemInterface */
    private $menu;

    /** @var KnpMenuEvent */
    private $event;

    public function __construct()
    {
    }

    public static function getSubscribedEvents(): array
    {
        return [
            KnpMenuEvent::class => ['onSetupMenu', 100],
        ];
    }

    public function onSetupMenu(KnpMenuEvent $event): void
    {
        $this->event = $event;
        $this->menu = $this->event->getMenu();

            $this->addHome();
    }


    private function addHome(): void
    {
        $this->menu->addChild('home', [
            'route' => 'home',
            'label' => 'Page d\'accueil',
            'childOptions' => $this->event->getChildOptions(),
        ])->setLabelAttribute('icon', 'fas fa-home');
    }

}
