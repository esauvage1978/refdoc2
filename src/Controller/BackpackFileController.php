<?php

namespace App\Controller;

use App\Entity\Backpack;
use App\Entity\BackpackFile;
use App\Form\File\BackpackFileType;
use App\Manager\BackpackFileManager;
use App\Form\File\BackpackFileAddType;
use App\Repository\BackpackRepository;
use App\Repository\BackpackFileRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\File\File;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\String\Slugger\SluggerInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;

/**
 * @package App\Controller
 */
class BackpackFileController extends AbstractGController
{


    public function __construct(
        BackpackFileRepository $repository,
        BackpackFileManager $manager
    ) {
        $this->repository = $repository;
        $this->manager = $manager;
        $this->domaine = 'backpackfile';
    }

    /**
     * @Route("/backpackfile/{parent_id}/add", name="backpack_file_add", methods={"GET","POST"})
     * @IsGranted("ROLE_USER")
     */
    public function add(
        Request $request,
        BackpackRepository $backpackRepository,
        string $parent_id
    ) {
        $backpack = $backpackRepository->find($parent_id);
        $file = new BackpackFile();
        $file->setBackpack($backpack);

        $form = $this->createForm(BackpackFileAddType::class, $file, ['action' => $this->generateUrl($request->get('_route'), ['parent_id' => $parent_id])]);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            if ($this->manager->save($file)) {
                return new Response('ok');
            } else {
                $this->addFlash(self::DANGER, self::MSG_MODIFY_ERROR . $this->manager->getErrors($file));
            }
        }

        return $this->render('backpack/_edit/_backpack_file_form_add.html.twig', [
            'file' => $file,
            self::FORM => $form->createView(),
        ]);
    }


    /**
     * @Route("/backpackfile/{id}/edit", name="backpack_file_edit", methods={"GET","POST"})
     * @IsGranted("ROLE_USER")
     */
    public function edit(
        Request $request,
        BackpackFile $item
    ) {
        $itemOld = clone ($item);
        $form = $this->createForm(
            BackpackFileType::class,
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

        return $this->render('backpack/_edit/_backpack_file_form_edit.html.twig', [
            'file' => $item,
            self::FORM => $form->createView(),
        ]);
    }

    /**
     * @Route("/backpackfile/{id}/delete", name="backpack_file_delete", methods={"GET"})
     * @IsGranted("ROLE_USER")
     */
    public function deleteFileAction(
        BackpackFile $item
    ) {
        $this->manager->remove($item);

        return new Response('ok');
    }

    /**
     * @Route("/backpackfile/{id}", name="backpack_files_show", methods={"GET"})
     * @IsGranted("ROLE_USER")
     */
    public function showPJ_edit(Backpack $item)
    {
        return $this->render('backpack/_edit/_backpackfile.html.twig', [
            'item' => $item
        ]);
    }

    /**
     * @Route("/backpackfile/{id}/showsecure", name="backpack_file_show_secure", methods={"GET"})
     * @IsGranted("ROLE_USER")
     */
    public function actionFileShowSecureAction(
        SluggerInterface $slugger,
        BackpackFile $actionFile
    ): Response {

        $actionFile->setNbrView($actionFile->getNbrView() + 1);
        $this->manager->save($actionFile);

        $file = new File($actionFile->getHref());

        return $this->file($file, $slugger->slug($actionFile->getTitle()) . '.' . $actionFile->getFileExtension());
    }
}
