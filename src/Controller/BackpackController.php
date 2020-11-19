<?php

namespace App\Controller;

use App\Dto\UserDto;
use App\Security\Role;
use App\Helper\Slugger;
use App\Dto\BackpackDto;
use App\Dto\MProcessDto;
use App\Entity\Backpack;
use App\Tree\BackpackTree;
use App\Security\CurrentUser;
use App\Security\BackpackVoter;
use App\Helper\ParamsInServices;
use App\Manager\BackpackManager;
use App\Service\BackpackForTree;
use App\Service\BackpackMakerDto;
use App\Form\Backpack\BackpackType;
use App\Form\Backpack\BackpackNewType;
use App\Repository\BackpackRepository;
use App\Repository\BackpackDtoRepository;
use App\Repository\BackpackFileRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\File\File;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;

/**
 * Class ThematicController
 * @package App\Controller
 */
class BackpackController extends AbstractGController
{

    public function __construct(
        BackpackRepository $repository,
        backpackManager $manager
    ) {
        $this->repository = $repository;
        $this->manager = $manager;
        $this->domaine = 'backpack';
    }

    /**
     * @Route("/backpack/add", name="backpack_add", methods={"GET","POST"})
     * @IsGranted("ROLE_USER")
     */
    public function add(Request $request)
    {
        $this->denyAccessUnlessGranted(BackpackVoter::CREATE, null);

        return $this->editAction($request, new Backpack(), BackpackNewType::class, false);
    }


    /**
     * @Route("/backpack/{id}/edit", name="backpack_edit", methods={"GET","POST"})
     * @IsGranted("ROLE_USER")
     */
    public function edit(Request $request, Backpack $item)
    {
        $this->denyAccessUnlessGranted(BackpackVoter::UPDATE, $item);
        $itemOld = clone ($item);
        $form = $this->createForm(BackpackType::class, $item);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            if ($this->manager->save($item)) {
                $this->addFlash(self::SUCCESS, self::MSG_MODIFY);
                //$backpackHistory->compare($itemOld, $item);
            } else {
                $this->addFlash(self::DANGER, self::MSG_MODIFY_ERROR . $this->manager->getErrors($item));
            }
        }

        return $this->render('backpack/edit.html.twig', [
            'item' => $item,
            self::FORM => $form->createView(),
        ]);
    }

    /**
     * @Route("/backpacks", name="backpacks", methods={"GET"})
     * @IsGranted("ROLE_USER")
     */
    public function treeView(Request $request, BackpackDto $dto)
    {
        //$renderArray = $this->backpackForTree->getDatas($this->container, $request, null, $dto);
        return $this->render('backpack/tree.html.twig');
    }

    /**
     * @Route("/backpack/{id}/history", name="backpack_history", methods={"GET","POST"})
     * @return Response
     * @IsGranted("ROLE_USER")
     */
    public function history(Request $request, Backpack $item): Response
    {
        return $this->render('backpack/history.html.twig', [
            'item' => $item,
            'histories' => null
        ]);
    }

    /**
     * @Route("/backpack/{id}", name="backpack_del", methods={"DELETE"})
     * @IsGranted("ROLE_USER")
     */
    public function delete(Request $request, Backpack $item)
    {
        $this->denyAccessUnlessGranted(BackpackVoter::DELETE, $item);

        $dto = new BackpackDto();
        $dto
            ->setCurrentState($item->getStateCurrent())
            ->setMProcessDto((new MProcessDto())->setId($item->getMProcess()->getId()))
            ->setVisible(BackpackDto::TRUE);

        if ($this->isCsrfTokenValid('delete' . $item->getId(), $request->request->get('_token'))) {
            $this->addFlash(self::SUCCESS, self::MSG_DELETE);
            $this->manager->remove($item);
        }

        $renderArray = $this->backpackForTree->getDatas($this->container, $request, null, $dto);
        return $this->render('backpack/tree.html.twig', $renderArray);
    }
}
