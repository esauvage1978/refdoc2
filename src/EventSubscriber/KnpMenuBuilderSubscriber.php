<?php

declare(strict_types=1);

namespace App\EventSubscriber;

use App\Security\Role;
use Knp\Menu\ItemInterface;
use App\Security\CurrentUser;
use KevinPapst\AdminLTEBundle\Event\KnpMenuEvent;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;

class KnpMenuBuilderSubscriber implements EventSubscriberInterface
{
    /** @var CurrentUser */
    private $currentUser;

    /** @var ItemInterface */
    private $menu;

    /** @var KnpMenuEvent */
    private $event;

    public function __construct(CurrentUser $currentUser)
    {
        $this->currentUser = $currentUser;
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

        if ($this->currentUser->isAuthenticatedRemember() && Role::isUser($this->currentUser->getUser())) {
            $this->addHome();
            $this->addBackpack();
            $this->addProfil();
            $this->addSubscription();
            $this->addAdmin();
            $this->addDeconnexion();
        } elseif ($this->currentUser->isAuthenticatedRemember()) {
            $this->addHome();
            $this->addProfil();
            $this->addConnexion();
        } else {
            $this->addHome();
            $this->addConnexion();
        }
    }


    private function addHome(): void
    {
        $this->menu->addChild('home', [
            'route' => 'home',
            'label' => 'Page d\'accueil',
            'childOptions' => $this->event->getChildOptions(),
        ])->setLabelAttribute('icon', 'fas fa-home');
    }

    private function addDeconnexion(): void
    {
        $this->menu->addChild(
            'logout',
            ['route' => 'user_logout', 'label' => 'Déconnexion', 'childOptions' => $this->event->getChildOptions()]
        )->setLabelAttribute('icon', 'fas fa-sign-out-alt');
    }

    private function addConnexion(): void
    {
        $this->menu->addChild(
            'login',
            ['route' => 'user_login', 'label' => 'Connexion', 'childOptions' => $this->event->getChildOptions()]
        )->setLabelAttribute('icon', 'fas fa-sign-in-alt');
    }

    private function addProfil(): void
    {
        $this->menu->addChild('profil', [
            'route' => 'profil',
            'label' => 'Votre compte',
            'childOptions' => $this->event->getChildOptions(),
        ])->setLabelAttribute('icon', 'fas fa-user');
    }

    private function addAdmin(): void
    {
        if (!Role::isAdmin($this->currentUser->getUser())) {
            return;
        }

        $this->menu->addChild('admin', [
            'route' => 'admin',
            'label' => 'Administration',
            'childOptions' => $this->event->getChildOptions(),
        ])->setLabelAttribute('icon', 'fas fa-wrench');
    }


    private function addSubscription(): void
    {
        $this->menu->addChild('subscription', [
            'route' => 'mySubscription',
            'label' => 'Vos abonnements',
            'childOptions' => $this->event->getChildOptions(),
        ])->setLabelAttribute('icon', 'fab fa-chromecast');
    }

    private function addBackpack()
    {
        $this->menu->addChild(
            'backpack',
            [
                'route' => 'home',
                'label' => 'Porte-documents',
                'childOptions' => $this->event->getChildOptions(),
                'options' => ['branch_class' => 'treeview']
            ]
        )->setLabelAttribute('icon', 'nav-icon fas fa-suitcase');

        $this->menu->getChild('backpack')->addChild(
            'backpack-add',
            [
                'route' => 'backpack_add',
                'label' => 'Création',
                'childOptions' => $this->event->getChildOptions()
            ]
        )->setLabelAttribute('icon', 'fas fa-plus-circle');
    }
}
