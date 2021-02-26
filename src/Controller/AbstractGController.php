<?php

declare(strict_types=1);

namespace App\Controller;

use App\Manager\InterfaceManager;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;

abstract class AbstractGController extends AbstractController
{
    public const SUCCESS = 'success';
    public const DANGER = 'danger';
    public const INFO = 'info';
    public const WARNING = 'warning';

    public const MSG_CREATE = 'La création est effectuée !';
    public const MSG_CREATE_ERROR = 'Une erreur est survenue lors de la création, merci de corriger : ';
    public const MSG_MODIFY = 'La modification est effectuée !';
    public const MSG_MODIFY_ERROR = 'Une erreur est survenue lors de la modification, merci de corriger : ';
    public const MSG_DELETE = 'La suppression est effectuée !';
    public const MSG_DELETE_ERROR = 'Une erreur est intervenue, la suppression n\'a pas eu lieu !';

    public const FORM = 'form';

    /** @var Request */
    protected $request;
    protected $repository;
    /** @var InterfaceManager */
    protected $manager;
    protected $domaine;

    public function listAction(string $template = 'list')
    {
        return $this->render(
            $this->domaine . '/' . $template . '.html.twig',
            [
                'items' => $this->repository->findAllForAdmin(),
            ]
        );
    }

    public function deleteAction(
        Request $request,
        $item
    ) {
        if ($this->isCsrfTokenValid('delete' . $item->getId(), $request->request->get('_token'))) {
            $this->addFlash(self::SUCCESS, self::MSG_DELETE);
            $this->manager->remove($item);
        }

        return $this->redirectToRoute($this->domaine . '_list');
    }

    public function showAction(
        Request $request,
        $item
    ) {
        return $this->render($this->domaine . '/show.html.twig', ['item' => $item]);
    }

    public function editAction(
        Request $request,
        $item,
        $class,
        $edit = true
    ) {
        $form = $this->createForm($class, $item);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            if ($this->manager->save($item)) {
                $this->addFlash(self::SUCCESS, self::MSG_MODIFY);

                return $this->redirectToRoute($this->domaine . '_edit', ['id' => $item->getId()]);
            }

            $this->addFlash(self::DANGER, self::MSG_MODIFY_ERROR . $this->manager->getErrors($item));
        }
        return $this->render($this->domaine . '/' . ($edit ? 'edit' : 'add') . '.html.twig', [
            'item' => $item,
            'form' => $form->createView(),
        ]);
    }
}
