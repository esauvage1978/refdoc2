<?php

declare(strict_types=1);

namespace App\Controller;

use App\Entity\MProcess;
use App\Form\Admin\MProcessType;
use App\Manager\MProcessManager;
use App\Repository\MProcessRepository;
use App\Repository\UserRepository;
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
     * @Route("/admin/mprocess/sort/apply", name="admin_mprocess_sort_apply", methods={"GET"})
     *
     * @IsGranted("ROLE_GESTIONNAIRE")
     */
    public function sortApply(Request $request)
    {
        $result=$request->get('result');
        $datas=explode('_',$result);

        foreach ($datas as $key => $id){
            $rubric=$this->repository->find($id);
            $rubric->setShowOrder($key);
            $this->manager->save($rubric);
        }
        return $this->redirectToRoute('admin_mprocess_list');
    }
 
    /**
    * @Route("/admin/mprocess/sort", name="admin_mprocess_sort", methods={"GET"})
     * @IsGranted("ROLE_GESTIONNAIRE")
     */
    public function sort()
    {
        return $this->render($this->domaine . '/sort.html.twig',
            [
                'items' => $this->repository->findAllForAdmin()
            ]);
    }    

    /**
     * @Route("/admin/mprocess/permission", name="admin_mprocess_list_permission", methods={"GET"})
     * @IsGranted("ROLE_USER")
     */
    public function listPermission(UserRepository $userRepository )
    {
        return $this->render(
            $this->domaine . '/list_permission.html.twig',
            [
                'items' => $this->repository->findAllForAdmin(),
                'users' => $userRepository->findBy(['isEnable'=>true])
            ]
        );
        return $this->listAction('list_permission');
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
