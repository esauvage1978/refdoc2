<?php

namespace App\Tree;

use App\Helper\ParamsInServices;
use Psr\Container\ContainerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Exception\InvalidParameterException;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;

abstract class AbstractTree implements InterfaceTree
{
    /**
     * @var string
     */
    protected $idName;

    protected $nbrItems=0;

    /**
     * @var array
     */
    protected $tree;

    /**
     * @var ContainerInterface
     */
    private $container;

    /**
     * @var Request
     */
    protected $request;

    /**
     * @var string
     */
    private $route;

    /**
     * @var array
     */
    private $parameter;

    /**
     * @var boolean
     */
    protected $developed;

    /**
     * @var string
     */
    protected $itemRequestId;

    protected $paramsInServices;

    public function __construct(
        ContainerInterface $container,
        Request $request,
        ParamsInServices $paramsInServices
    )
    {
        $this->container = $container;
        $this->request = $request;

        $this->idName='idItem';
        $tree = null;
        $this->developed = false;
        $this->parameter = [];

        $this->paramsInServices=$paramsInServices;
    }

    public function initialise($items): self
    {
        $this->items = $items;

        $this->nbrItems=count($this->items);

        if(!isset($this->item)) {
            if ($this->request->query->has($this->idName)) {
                $this->itemRequestId = $this->request->query->get($this->idName);
                $this->findItem();
            } else if ($this->nbrItems > 0) {
                $this->item = $this->items[0];
            }
        }
        return $this;
    }

    protected function findItem()
    {
        foreach ($this->items as $item) {
            if ($this->itemRequestId === (string)$item->getId()) {
                $this->item = $item;
            }
        }

    }


    protected function getParent()
    {
        return '#';
    }

    protected function addBranche($id, $data_courant, $parent, $opened = true, $isEnable=true,$icon='')
    {
        $this->tree[] = [
            'id' => $id,
            'parent' => $parent,
            'text' => '<span class="'. ($isEnable?'text-secondary':'text-danger').'">'. $data_courant . "</span>",
            'icon' => $icon,
            'state' => [
                'opened' => $opened,
            ],
        ];
    }



    public function setParameter(array $parameter): self
    {
        $this->parameter = $parameter;
        return $this;
    }

    public function Developed(): self
    {
        $this->developed = true;
        return $this;
    }

    public function getItems()
    {
        return $this->items;
    }

    public function getCountItems()
    {
        return $this->nbrItems;
    }

    public function getItem()
    {
        return $this->item;
    }

    public function setItem($item): self
    {
        $this->item = $item;
        return $this;
    }

    protected function getTreeCheck()
    {
        if($this->nbrItems==0) {
            $this->tree[] = [
                'id' => '0',
                'parent' => '#',
                'text' => '<span class="text-info">Aucun élément à afficher</span> ',
                'icon' => 'fas fa-empty text-info ',
                'a_attr' => [
                    'href' => '',
                ],
                'state' => [
                    'selected' => true,
                    'opened' => true,
                ],
            ];
        } else {
            if (!isset($this->items)) {
                throw new InvalidParameterException('Vous devez initialiser la classe avec la fonction initialise');
            }
            if (!isset($this->item)) {
                throw new InvalidParameterException('La variable item n\'est pas définie');
            }
        }
    }

    protected function generateUrl($id)
    {
        if (!isset($this->route)) {
            throw new InvalidParameterException('Aucune route définie');
        }

        $this->parameter = array_merge($this->parameter, [$this->idName => $id]);

        return $this->container->get('router')->generate($this->route, $this->parameter, UrlGeneratorInterface::ABSOLUTE_PATH);
    }

    public function setRoute(string $route): self
    {
        $this->route = $route;
        return $this;
    }

    /**
     * @return string
     */
    public function getIdName(): string
    {
        return $this->idName;
    }

    /**
     * @param string $idName
     */
    public function setIdName(string $idName): void
    {
        $this->idName = $idName;
    }
}