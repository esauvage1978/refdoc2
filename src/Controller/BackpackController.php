<?php

namespace App\Controller;

use App\Dto\BackpackDto;
use App\Dto\MProcessDto;
use App\Entity\Backpack;
use App\History\HistoryShow;
use App\Security\BackpackVoter;
use App\Manager\BackpackManager;
use App\Service\BackpackForTree;
use App\Form\Backpack\BackpackType;
use App\Form\Backpack\BackpackNewType;
use App\Repository\BackpackRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;

/**
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
        BackpackForTree $backpackForTree,
        backpackManager $manager
    ) {
        $this->repository = $repository;
        $this->manager = $manager;
        $this->backpackForTree = $backpackForTree;
        $this->domaine = 'backpack';
    }

    /**
     * @Route("/backpack/add", name="backpack_add", methods={"GET","POST"})
     * @IsGranted("ROLE_USER")
     */
    public function add(Request $request)
    {
        $this->denyAccessUnlessGranted(BackpackVoter::CREATE, null);

        $item=new Backpack();
        $form = $this->createForm(BackpackNewType::class, $item);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            if ($this->manager->save($item)) {
                if($item->getRef()===null) {
                    $this->manager->save($item);
                }
                $this->addFlash(self::SUCCESS, self::MSG_CREATE);
                return $this->redirectToRoute('backpack_edit', ['id' => $item->getId()]);
            } else {
                $this->addFlash(self::DANGER, self::MSG_CREATE_ERROR . $this->manager->getErrors($item));
            }
        }

        return $this->render('backpack/add.html.twig', [
            'item' => $item,
            self::FORM => $form->createView()
        ]);
    }


    /**
     * @Route("/backpack/{id}", name="backpack_show", methods={"GET"})
     * @IsGranted("ROLE_USER")
     */
    public function show(Backpack $item)
    {

        return $this->render('backpack/show.html.twig', [
            'item' => $item
        ]);
    }



    /**
     * @Route("/backpack/test/{id}", name="backpack_test", methods={"GET"})
     * @IsGranted("ROLE_USER")
     */
    public function test(Backpack $item)
    {
        $this->denyAccessUnlessGranted(BackpackVoter::READ, $item);

        return $this->render('backpack/test.html.twig', [
            'item' => $item
        ]);
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
                if($item->getRef()===null) {
                    $this->manager->save($item);
                }
                $this->addFlash(self::SUCCESS, self::MSG_MODIFY);
                //$this->manager->historizeLinks($item, $item->getBackpackLinks(), $links);
                $this->manager->historize($item, $itemOld);
            } else {
                $this->addFlash(self::DANGER, self::MSG_MODIFY_ERROR . $this->manager->getErrors($item));
            }
        }

        return $this->render('backpack/edit.html.twig', [
            'item' => $item,
            self::FORM => $form->createView()
        ]);
    }

    /**
     * @Route("/backpack/{id}/classify", name="backpack_classify", methods={"GET","POST"})
     * @IsGranted("ROLE_USER")
     */
    public function classify(Request $request, Backpack $item)
    {
        $this->denyAccessUnlessGranted(BackpackVoter::CLASSIFY, $item);
        $itemOld = clone ($item);
        $form = $this->createForm(BackpackType::class, $item);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            if ($this->manager->save($item)) {
                if($item->getRef()===null) {
                    $this->manager->save($item);
                }
                $this->addFlash(self::SUCCESS, self::MSG_MODIFY);
                $this->manager->historize($item, $itemOld);
            } else {
                $this->addFlash(self::DANGER, self::MSG_MODIFY_ERROR . $this->manager->getErrors($item));
            }
        }

        return $this->render('backpack/classify.html.twig', [
            'item' => $item,
            self::FORM => $form->createView()
        ]);
    }


    /**
     * @Route("/backpack/{id}/content", name="backpack_content", methods={"GET","POST"})
     * @IsGranted("ROLE_ADMIN")
     */
    public function content(Request $request, Backpack $item)
    {
        $this->denyAccessUnlessGranted(BackpackVoter::CLASSIFY, $item);
        $itemOld = clone ($item);
        $form = $this->createForm(BackpackType::class, $item);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            if ($this->manager->save($item)) {
                $this->addFlash(self::SUCCESS, self::MSG_MODIFY);
            } else {
                $this->addFlash(self::DANGER, self::MSG_MODIFY_ERROR . $this->manager->getErrors($item));
            }
        }

        return $this->render('backpack/content.html.twig', [
            'item' => $item,
            self::FORM => $form->createView()
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
}
