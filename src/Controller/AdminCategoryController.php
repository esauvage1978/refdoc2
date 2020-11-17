<?php

declare(strict_types=1);

namespace App\Controller;

use App\Entity\Category;
use App\Form\Admin\CategoryType;
use App\Manager\CategoryManager;
use App\Repository\CategoryRepository;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class AdminCategoryController extends AbstractGController
{
    public function __construct(
        CategoryRepository $repository,
        CategoryManager $manager
    ) {
        $this->repository = $repository;
        $this->manager = $manager;
        $this->domaine = 'admin_category';
    }

    /**
     * @Route("/admin/category", name="admin_category_list", methods={"GET"})
     * @IsGranted("ROLE_USER")
     */
    public function list()
    {
        return $this->listAction();
    }

    /**
     * @Route("/admin/category/add", name="admin_category_add", methods={"GET","POST"})
     * @IsGranted("ROLE_GESTIONNAIRE")
     */
    public function add(Request $request)
    {
        return $this->editAction($request, new category(), categoryType::class, false);
    }

    /**
     * @Route("/admin/category/{id}", name="admin_category_del", methods={"DELETE"})
     * @IsGranted("ROLE_GESTIONNAIRE")
     */
    public function delete(Request $request, category $item)
    {
        return $this->deleteAction($request, $item);
    }

    /**
     * @Route("/admin/category/{id}", name="admin_category_show", methods={"GET"})
     * @IsGranted("ROLE_USER")
     */
    public function show(Request $request, category $item)
    {
        return $this->showAction($request, $item);
    }

    /**
     * @Route("/admin/category/{id}/edit", name="admin_category_edit", methods={"GET","POST"})
     * @IsGranted("ROLE_GESTIONNAIRE")
     */
    public function edit(Request $request, category $item)
    {
        return $this->editAction($request, $item, categoryType::class);
    }
}
