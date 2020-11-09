<?php

declare(strict_types=1);

namespace App\Controller;

use App\Entity\Process;
use App\Form\Admin\ProcessType;
use App\Manager\ProcessManager;
use App\Repository\ProcessRepository;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class AdminProcessController extends AbstractGController
{
    public function __construct(
        ProcessRepository $repository,
        ProcessManager $manager
    ) {
        $this->repository = $repository;
        $this->manager = $manager;
        $this->domaine = 'admin_process';
    }

    /**
     * @Route("/admin/process", name="admin_process_list", methods={"GET"})
     * @IsGranted("ROLE_USER")
     */
    public function list()
    {
        return $this->listAction();
    }

    /**
     * @Route("/admin/process/add", name="admin_process_add", methods={"GET","POST"})
     * @IsGranted("ROLE_GESTIONNAIRE")
     */
    public function add(Request $request)
    {
        return $this->editAction($request, new Process(), ProcessType::class, false);
    }

    /**
     * @Route("/admin/process/{id}", name="admin_process_del", methods={"DELETE"})
     * @IsGranted("ROLE_GESTIONNAIRE")
     */
    public function delete(Request $request, Process $item)
    {
        return $this->deleteAction($request, $item);
    }

    /**
     * @Route("/admin/process/{id}", name="admin_process_show", methods={"GET"})
     * @IsGranted("ROLE_USER")
     */
    public function show(Request $request, Process $item)
    {
        return $this->showAction($request, $item);
    }

    /**
     * @Route("/admin/process/{id}/edit", name="admin_process_edit", methods={"GET","POST"})
     * @IsGranted("ROLE_GESTIONNAIRE")
     */
    public function edit(Request $request, Process $item)
    {
        return $this->editAction($request, $item, ProcessType::class);
    }
}
