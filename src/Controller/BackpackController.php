<?php

namespace App\Controller;

use App\Dto\UserDto;
use App\Security\Role;
use App\Helper\Slugger;
use App\Dto\BackpackDto;
use App\Dto\MProcessDto;
use App\Entity\Backpack;
use App\Tree\BackpackTree;
use App\History\HistoryShow;
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



    /**
     * @var BackpackForTree
     */
    private $backpackForTree;

    public function __construct(
        BackpackRepository $repository,
        backpackManager $manager,
        BackpackForTree $backpackForTree
    ) {
        $this->repository = $repository;
        $this->manager = $manager;
        $this->domaine = 'backpack';
        $this->backpackForTree = $backpackForTree;
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
                $this->manager->historize($item, $itemOld);
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
     * @Route("/backpack/{id}/history", name="backpack_history", methods={"GET","POST"})
     * @return Response
     * @IsGranted("ROLE_USER")
     */
    public function historyAction(Request $request, Backpack $item): Response
    {
        $this->denyAccessUnlessGranted(BackpackVoter::READ, $item);
        $historyShow = new HistoryShow(
            $this->generateUrl('backpack_edit', ['id' => $item->getId()]),
            "Porte-document : " . $item->getName(),
            "Historiques des modifications du porte-document"
        );

        return $this->render('backpack/history.html.twig', [
            'item' => $item,
            'histories' => $item->getHistories(),
            'data' => $historyShow->getParams()
        ]);
    }

    /**
     * @Route("/backpacks", name="backpacks", methods={"GET"})
     * @IsGranted("ROLE_USER")
     */
    public function treeView(Request $request, BackpackDto $dto)
    {
        $renderArray = $this->backpackForTree->getDatas($this->container, $request, null, $dto);
        return $this->render('backpack/tree.html.twig', $renderArray);
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
            ->setStateCurrent($item->getStateCurrent())
            ->setMProcessDto((new MProcessDto())->setId($item->getMProcess()->getId()))
            ->setVisible(BackpackDto::TRUE);

        if ($this->isCsrfTokenValid('delete' . $item->getId(), $request->request->get('_token'))) {
            $this->addFlash(self::SUCCESS, self::MSG_DELETE);
            $this->manager->remove($item);
        }

        $renderArray = $this->backpackForTree->getDatas($this->container, $request, null, $dto);
        return $this->render('backpack/tree.html.twig', $renderArray);
    }

    public function widgetBackpacksAction(): Response
    {
        return $this->render('backpack/_widgetBackpacks.html.twig');
    }

    /**
     * @Route("/backpack/{id}/file/{fileId}", name="backpack_file_show", methods={"GET"})
     * @IsGranted("ROLE_USER")
     */
    public function actionFileShowAction(
        Request $request,
        Backpack $backpack,
        string $fileId,
        BackpackFileRepository $backpackFileRepository
    ): Response {

        $actionFile = $backpackFileRepository->find($fileId);

        $file = new File($actionFile->getHref());

        return $this->file($file, Slugger::slugify($actionFile->getTitle()) . '.' . $actionFile->getFileExtension());
    }

    /**
     * @Route("/backpacks/draftupdatable", name="backpacks_draft_updatable", methods={"GET"})
     * @IsGranted("ROLE_USER")
     */
    public function state_draft_updatable(Request $request)
    {
        $renderArray = $this->backpackForTree->getDatas($this->container, $request, BackpackMakerDto::DRAFT_UPDATABLE);
        return $this->render('backpack/tree.html.twig', $renderArray);
    }


    /**
     * @Route("/backpacks/mydraftupdatable", name="backpacks_mydraft_updatable", methods={"GET"})
     * @IsGranted("ROLE_USER")
     */
    public function state_mydraft_updatable(Request $request)
    {
        $renderArray = $this->backpackForTree->getDatas($this->container, $request, BackpackMakerDto::MY_DRAFT_UPDATABLE);
        return $this->render('backpack/tree.html.twig', $renderArray);
    }


    /**
     * @Route("/backpacks/draft", name="backpacks_draft", methods={"GET"})
     * @IsGranted("ROLE_USER")
     */
    public function state_draft(Request $request)
    {
        $renderArray = $this->backpackForTree->getDatas($this->container, $request, BackpackMakerDto::DRAFT);
        return $this->render('backpack/tree.html.twig', $renderArray);
    }


    /**
     * @Route("/backpacks/abandonned", name="backpacks_abandonned", methods={"GET"})
     * @IsGranted("ROLE_USER")
     */
    public function state_abandonned(Request $request)
    {
        $renderArray = $this->backpackForTree->getDatas($this->container, $request, BackpackMakerDto::ABANDONNED);
        return $this->render('backpack/tree.html.twig', $renderArray);
    }
    /**
     * @Route("/backpacks/abandonnedupdatable", name="backpacks_abandonned_updatable", methods={"GET"})
     * @IsGranted("ROLE_USER")
     */
    public function state_abandonned_updatable(Request $request)
    {
        $renderArray = $this->backpackForTree->getDatas($this->container, $request, BackpackMakerDto::ABANDONNED_UPDATABLE);
        return $this->render('backpack/tree.html.twig', $renderArray);
    }

    /**
     * @Route("/backpacks/myabandonnedupdatable", name="backpacks_myabandonned_updatable", methods={"GET"})
     * @IsGranted("ROLE_USER")
     */
    public function state_myabandonned_updatable(Request $request)
    {
        $renderArray = $this->backpackForTree->getDatas($this->container, $request, BackpackMakerDto::MY_ABANDONNED_UPDATABLE);
        return $this->render('backpack/tree.html.twig', $renderArray);
    }


    /**
     * @Route("/backpacks/toresume", name="backpacks_toResume", methods={"GET"})
     * @IsGranted("ROLE_USER")
     */
    public function state_toresume(Request $request)
    {
        $renderArray = $this->backpackForTree->getDatas($this->container, $request, BackpackMakerDto::TO_RESUME);
        return $this->render('backpack/tree.html.twig', $renderArray);
    }
    /**
     * @Route("/backpacks/toresumeupdatable", name="backpacks_toResume_updatable", methods={"GET"})
     * @IsGranted("ROLE_USER")
     */
    public function state_toresume_updatable(Request $request)
    {
        $renderArray = $this->backpackForTree->getDatas($this->container, $request, BackpackMakerDto::TO_RESUME_UPDATABLE);
        return $this->render('backpack/tree.html.twig', $renderArray);
    }

    /**
     * @Route("/backpacks/mytoresumeupdatable", name="backpacks_mytoResume_updatable", methods={"GET"})
     * @IsGranted("ROLE_USER")
     */
    public function state_mytoresume_updatable(Request $request)
    {
        $renderArray = $this->backpackForTree->getDatas($this->container, $request, BackpackMakerDto::MY_TO_RESUME_UPDATABLE);
        return $this->render('backpack/tree.html.twig', $renderArray);
    }
}
