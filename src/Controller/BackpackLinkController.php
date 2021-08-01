<?php

namespace App\Controller;

use App\Entity\Backpack;
use App\Entity\BackpackLink;
use App\Form\File\BackpackLinkType;
use App\Manager\BackpackLinkManager;
use App\Repository\BackpackRepository;
use App\Repository\BackpackLinkRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;

/**
 * @package App\Controller
 */
class BackpackLinkController extends AbstractGController
{


    public function __construct(
        BackpackLinkRepository $repository,
        BackpackLinkManager $manager
    ) {
        $this->repository = $repository;
        $this->manager = $manager;
        $this->domaine = 'backpacklink';
    }

    /**
     * @Route("/backpacklink/{parent_id}/add", name="backpack_link_add", methods={"GET","POST"})
     * @IsGranted("ROLE_USER")
     */
    public function add(
        Request $request,
        BackpackRepository $backpackRepository,
        string $parent_id
    ) {
        $backpack = $backpackRepository->find($parent_id);
        $link = new BackpackLink();
        $link->setBackpack($backpack);

        $form = $this->createForm(BackpackLinkType::class, $link, ['action' => $this->generateUrl($request->get('_route'), ['parent_id' => $parent_id])]);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            if ($this->manager->save($link)) {
                return new Response('ok');
            } else {
                $this->addFlash(self::DANGER, self::MSG_MODIFY_ERROR . $this->manager->getErrors($link));
            }
        }

        return $this->render('backpack/_edit/_backpack_link_form_add.html.twig', [
            'link' => $link,
            self::FORM => $form->createView(),
        ]);
    }


    /**
     * @Route("/backpacklink/{id}/edit", name="backpack_link_edit", methods={"GET","POST"})
     * @IsGranted("ROLE_USER")
     */
    public function edit(
        Request $request,
        string $id,
        BackpackLinkRepository $backpackLinkRepository
    ) {
        $link = $backpackLinkRepository->find($id);

        $form = $this->createForm(
            BackpackLinkType::class,
            $link,
            ['action' => $this->generateUrl($request->get('_route'), ['id' => $id])]
        );

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            if ($this->manager->save($link)) {
                return new Response('ok');
            } else {
                $this->addFlash(self::DANGER, self::MSG_MODIFY_ERROR . $this->manager->getErrors($link));
            }
        }

        return $this->render('backpack/_edit/_backpack_link_form_edit.html.twig', [
            'link' => $link,
            self::FORM => $form->createView(),
        ]);
    }

    /**
     * @Route("/backpacklink/{id}/delete", name="backpack_link_delete", methods={"GET"})
     * @IsGranted("ROLE_USER")
     */
    public function deleteLinkAction(
        BackpackLink $item
    ) {
        $this->manager->remove($item);

        return new Response('ok');
    }

    /**
     * @Route("/backpacklink/{id}", name="backpack_links_show", methods={"GET"})
     * @IsGranted("ROLE_USER")
     */
    public function showPJ_edit(Backpack $item)
    {
        return $this->render('backpack/_edit/_backpacklink.html.twig', [
            'item' => $item
        ]);
    }
}
