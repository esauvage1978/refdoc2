<?php

declare(strict_types=1);

namespace App\Controller;

use App\Entity\MProcess;
use App\Form\Admin\MProcessType;
use App\Manager\MProcessManager;
use App\Repository\MProcessRepository;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class AdminMProcessController extends AbstractGController
{
    public function __construct(
        MProcessRepository $repository,
        MProcessManager $manager
    ) {
        $this->repository = $repository;
        $this->manager = $manager;
        $this->domaine = 'admin_mprocess';
    }

    /**
     * @Route("/admin/mprocess", name="admin_mprocess_list", methods={"GET"})
     * @IsGranted("ROLE_USER")
     */
    public function list()
    {
        return $this->listAction();
    }

    /**
     * @Route("/admin/mprocess/add", name="admin_mprocess_add", methods={"GET","POST"})
     * @IsGranted("ROLE_GESTIONNAIRE")
     */
    public function add(Request $request)
    {
        return $this->editAction($request, new MProcess(), MProcessType::class, false);
    }

    /**
     * @Route("/admin/mprocess/{id}", name="admin_mprocess_del", methods={"DELETE"})
     * @IsGranted("ROLE_GESTIONNAIRE")
     */
    public function delete(Request $request, MProcess $item)
    {
        return $this->deleteAction($request, $item);
    }

    /**
     * @Route("/admin/mprocess/{id}", name="admin_mprocess_show", methods={"GET"})
     * @IsGranted("ROLE_USER")
     */
    public function show(Request $request, MProcess $item)
    {
        return $this->showAction($request, $item);
    }

    /**
     * @Route("/admin/mprocess/{id}/edit", name="admin_mprocess_edit", methods={"GET","POST"})
     * @IsGranted("ROLE_GESTIONNAIRE")
     */
    public function edit(Request $request, MProcess $item)
    {
        return $this->editAction($request, $item, MProcessType::class);
    }
}
