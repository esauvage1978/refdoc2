<?php

declare(strict_types=1);

namespace App\Controller;

use App\Dto\UserDto;
use App\Entity\User;
use App\Dto\ProcessDto;
use App\Dto\MProcessDto;
use App\Entity\Backpack;
use App\Entity\Category;
use App\Service\BackpackRefGenerator;
use App\Repository\BackpackRepository;
use App\Repository\ProcessDtoRepository;
use App\Repository\MProcessDtoRepository;
use App\Service\BackpackRefControllator;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;

class AjaxBackpackController extends AbstractGController
{
    /**
     * @Route("/ajax/backpack/getrefcheck", name="ajax_backpack_getg_ref_check", methods={"GET","POST"})
     *
     * @return Response
     * @IsGranted("ROLE_USER")
     */
    public function AjaxBackpackGetRefCheck(Request $request, BackpackRepository $backpackRepository): Response
    {
        $ref =  $request->request->get('ref');
        $id =  $request->request->get('id');

        $backpack = $backpackRepository->find($id);
        $brc = new BackpackRefControllator($backpackRepository, $backpack);
        
        $msgOK="<span class='alert alert-success'>".$ref . " est utilisable</span>";
        $msgK0 = "<span class='alert alert-danger'>" . $ref . " est déjà utilisé</span>";

        return $this->json([
            'code' => 200,
            'value' => $brc->isUnique($ref)===true ? $msgOK : $msgK0,
            'message' => 'données transmises'
        ], 200);
    }

    /**
     * @Route("/ajax/backpack/{id}", name="ajax_backpack_get_ref", methods={"GET","POST"})
     *
     * @return Response
     * @IsGranted("ROLE_USER")
     */
    public function AjaxBackpackGetData(Request $request, BackpackRepository $backpackRepository, Backpack $backpack): Response
    {

        return $this->json([
            'code' => 200,
            'value' => $this->renderView('backpack/_show/_treeContent.html.twig', ['item' => $backpack]),
            'message' => 'données transmises'
        ], 200);
    }

    /**
     * @Route("/ajax/backpack/getref/{id}", name="ajax_backpack_get_data", methods={"GET","POST"})
     *
     * @return Response
     * @IsGranted("ROLE_USER")
     */
    public function AjaxBackpackGetRef(Request $request, BackpackRepository $backpackRepository, Backpack $backpack): Response
    {
        $bgr = new BackpackRefGenerator($backpackRepository, $backpack);

        return $this->json([
            'code' => 200,
            'value' => $bgr->get(),
            'message' => 'données transmises'
        ], 200);
    }


    /**
     * @Route("/ajax/getmpforcontribute", name="ajax_cmb_mp_for_contribute", methods={"GET","POST"})
     *
     * @return Response
     * @IsGranted("ROLE_USER")
     */
    public function AjaxGetMPForContribute(Request $request, MProcessDtoRepository $mProcessDtoRepository): Response
    {
        $dto = new MProcessDto();
        /**
         * @var User $user
         */
        $user = $this->getUser();

        $dto
            ->setForUpdate(MProcessDto::TRUE)
            ->setVisible(MProcessDto::TRUE);

        if (!$user->getUserParam()->getIsDoc()) {
            $dto->setUserDto((new UserDto())->setId($this->getUser()->getId()));
        }

        return $this->json([
            'code' => 200,
            'value' => $mProcessDtoRepository->findForCombobox($dto),
            'message' => 'données transmises'
        ], 200);
    }

    /**
     * @Route("/ajax/getpforcontribute", name="ajax_cmb_p_for_contribute", methods={"GET","POST"})
     *
     * @return Response
     * @IsGranted("ROLE_USER")
     */
    public function AjaxGetPForContribute(Request $request, ProcessDtoRepository $processDtoRepository): Response
    {
        $dto = new ProcessDto();
        /**
         * @var User $user
         */
        $user = $this->getUser();

        $dto
            ->setForUpdate(ProcessDto::TRUE)
            ->setVisible(ProcessDto::TRUE);

        if (!$user->getUserParam()->getIsDoc()) {
            $dto->setUserDto((new UserDto())->setId($this->getUser()->getId()));
        }

        return $this->json([
            'code' => 200,
            'value' => $processDtoRepository->findForCombobox($dto),
            'message' => 'données transmises'
        ], 200);
    }

    /**
     * @Route("/ajax/getcontentofcategory/{id}", name="ajax_get_content_of_category", methods={"GET","POST"})
     */
    public function AjaxGetContentOfCategory(Request $request, Category $cat): Response
    {
        return $this->json(
            $cat->getContent(),
            200
        );
    }

    /**
     * @Route("/ajax/getdir1", name="ajax_fill_combobox_dir1", methods={"GET","POST"})
     *
     * @return Response
     * @IsGranted("ROLE_USER")
     */
    public function AjaxGetDir1(Request $request, BackpackRepository $repository): Response
    {
        if ($request->isXmlHttpRequest()) {

            return $this->getDirJson($request, $repository, 1);
        }
        return new Response("Ce n'est pas une requête Ajax");
    }

    private function getDirIdMp(Request $request)
    {
        return $request->request->has('idMp') ? $request->request->get('idMp') : null;
    }
    private function getDirIdP(Request $request)
    {
        return $request->request->has('idP') ? $request->request->get('idP') : null;
    }
    private function getDirData(Request $request)
    {
        return $request->request->has('data') ? $request->request->get('data') : null;
    }
    private function getDirJson(Request $request, BackpackRepository $repository, int $dir)
    {
        $idMp = $this->getDirIdMp($request);
        $idP = $this->getDirIdP($request);
        $data = $this->getDirData($request);

        if ($dir == 1) {
            return $this->json($repository->findAllFillComboboxDir1($idMp, $idP));
        } else {
            return
                $this->json($repository->findAllFillComboboxDirOther($idMp, $idP, $data, $dir));
        }
    }

    /**
     * @Route("/ajax/getdir2", name="ajax_fill_combobox_dir2", methods={"POST"})
     *
     * @return Response
     * @IsGranted("ROLE_USER")
     */
    public function AjaxGetDir2(Request $request, BackpackRepository $repository): Response
    {
        if ($request->isXmlHttpRequest()) {

            return $this->getDirJson($request, $repository, 2);
        }

        return new Response("Ce n'est pas une requête Ajax");
    }

    /**
     * @Route("/ajax/getdir3", name="ajax_fill_combobox_dir3", methods={"POST"})
     *
     * @return Response
     * @IsGranted("ROLE_USER")
     */
    public function AjaxGetDir3(Request $request, BackpackRepository $repository): Response
    {
        if ($request->isXmlHttpRequest()) {

            return $this->getDirJson($request, $repository, 3);
        }

        return new Response("Ce n'est pas une requête Ajax");
    }

    /**
     * @Route("/ajax/getdir4", name="ajax_fill_combobox_dir4", methods={"POST"})
     *
     * @return Response
     * @IsGranted("ROLE_USER")
     */
    public function AjaxGetDir4(Request $request, BackpackRepository $repository): Response
    {
        if ($request->isXmlHttpRequest()) {

            return $this->getDirJson($request, $repository, 4);
        }

        return new Response("Ce n'est pas une requête Ajax");
    }

    /**
     * @Route("/ajax/getdir5", name="ajax_fill_combobox_dir5", methods={"POST"})
     *
     * @return Response
     * @IsGranted("ROLE_USER")
     */
    public function AjaxGetDir5(Request $request, BackpackRepository $repository): Response
    {
        if ($request->isXmlHttpRequest()) {

            return $this->getDirJson($request, $repository, 5);
        }

        return new Response("Ce n'est pas une requête Ajax");
    }
}
