<?php

namespace App\Controller;

use App\Entity\Backpack;
use App\Entity\BackpackFileSource;
use App\Form\File\BackpackFileSourceType;
use App\Manager\BackpackFileSourceManager;
use App\Repository\BackpackRepository;
use App\Repository\BackpackFileSourceRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\File\File;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\String\Slugger\SluggerInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;

/**
 * @package App\Controller
 */
class BackpackFileSourceController extends AbstractGController
{


    public function __construct(
        BackpackFileSourceRepository $repository,
        BackpackFileSourceManager $manager
    ) {
        $this->repository = $repository;
        $this->manager = $manager;
        $this->domaine = 'backpackfilesource';
    }

    /**
     * @Route("/backpackfilesource/{parent_id}/add", name="backpack_file_source_add", methods={"GET","POST"})
     * @IsGranted("ROLE_USER")
     */
    public function add(
        Request $request,
        BackpackRepository $backpackRepository,
        string $parent_id
    ) {
        $backpack = $backpackRepository->find($parent_id);
        $file = new BackpackFileSource();
        $file->setBackpack($backpack);

        $form = $this->createForm(BackpackFileSourceType::class, $file, ['action' => $this->generateUrl($request->get('_route'), ['parent_id' => $parent_id])]);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            if ($this->manager->save($file)) {
                return new Response('ok');
            } else {
                $this->addFlash(self::DANGER, self::MSG_MODIFY_ERROR . $this->manager->getErrors($file));
            }
        }

        return $this->render('backpack/_edit/_backpack_file_source_form_add.html.twig', [
            'file' => $file,
            self::FORM => $form->createView(),
        ]);
    }


    /**
     * @Route("/backpackfilesource/{id}/edit", name="backpack_file_source_edit", methods={"GET","POST"})
     * @IsGranted("ROLE_USER")
     */
    public function edit(
        Request $request,
        BackpackFileSource $item
    ) {
        $itemOld = clone ($item);
        $form = $this->createForm(
            BackpackFileSourceType::class,
            $item,
            ['action' => $this->generateUrl($request->get('_route'), ['id' => $item->getId()])]
        );

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            if ($this->manager->save($item)) {
                $this->manager->historize($item, $itemOld);
                return new Response('ok');
            } else {
                $this->addFlash(self::DANGER, self::MSG_MODIFY_ERROR . $this->manager->getErrors($item));
            }
        }

        return $this->render('backpack/_edit/_backpack_file_source_form_edit.html.twig', [
            'file' => $item,
            self::FORM => $form->createView(),
        ]);
    }

    /**
     * @Route("/backpackfilesource/{id}/delete", name="backpack_file_source_delete", methods={"GET"})
     * @IsGranted("ROLE_USER")
     */
    public function deleteFileAction(
        BackpackFileSource $item
    ) {
        $this->manager->remove($item);

        return new Response('ok');
    }

    /**
     * @Route("/backpackfilesource/{id}", name="backpack_files_source_show", methods={"GET"})
     * @IsGranted("ROLE_USER")
     */
    public function showPJ_edit(Backpack $item)
    {
        return $this->render('backpack/_edit/_backpackfilesource.html.twig', [
            'item' => $item
        ]);
    }

    /**
     * @Route("/backpackfilesource/{id}/showsecure", name="backpack_file_source_show_secure", methods={"GET"})
     * @IsGranted("ROLE_USER")
     */
    public function actionFileShowSecureAction(
        SluggerInterface $slugger,
        BackpackFileSource $actionFile
    ): Response {

        $actionFile->setNbrView($actionFile->getNbrView() + 1);
        $this->manager->save($actionFile);

        $file = new File($actionFile->getHref());

        return $this->file($file, $slugger->slug($actionFile->getTitle()) . '.' . $actionFile->getFileExtension());
    }
}
