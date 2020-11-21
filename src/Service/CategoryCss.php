<?php

namespace App\Service;

use App\Entity\Category;
use App\Helper\FileTools;
use App\Helper\ParamsInServices;
use App\Repository\CategoryRepository;


/**
 * @author Emmanuel SAUVAGE <emmanuel.sauvage@live.fr>
 * @version 1.0.0
 */
class CategoryCss
{
    /** @var Category[]*/
    private $categorys;

    /** @var ParamsInServices */
    private $paramsInServices;

    /**@var FileTools */
    private $fileTools;

    public function __construct(
        CategoryRepository $categoryRepository,
        ParamsInServices $paramsInServices,
        FileTools $fileTools
    ) {
        $this->categorys = $categoryRepository->findAll();
        $this->paramsInServices = $paramsInServices;
        $this->fileTools = $fileTools;
    }

    public function create()
    {
        $dir = $this->paramsInServices->get(ParamsInServices::ES_DIRECTORY_CSS);
        $filecss = "category.css";
        $content = '';

        if ($this->fileTools->exist($dir, $filecss)) {
            $this->fileTools->remove($dir, $filecss);
        }

        foreach ($this->categorys as $category) {
            $content = $content . $this->createOne($category);
        }
        
        $this->fileTools->write($dir, $filecss, $content, false);
    }

    private function createOne(Category $category): string
    {
        return '.category_' . $category->getId() .
            '{' .
            (null!==$category->getBgcolor() ? ('background-color:' . $category->getBgcolor() . ';') : '') .
            (null!==$category->getForecolor() ? ('color:' . $category->getForecolor() . ';') : '') .
            '}';
    }
}
