<?php

declare(strict_types=1);

namespace App\Controller;

use App\Entity\User;
use App\Dto\ProcessDto;
use App\Entity\Process;
use App\Dto\MProcessDto;
use App\Entity\MProcess;
use App\Form\Admin\UserType;
use App\Manager\UserManager;
use App\Repository\UserRepository;
use App\Repository\ProcessRepository;
use App\Repository\MProcessRepository;
use App\Repository\ProcessDtoRepository;
use App\Repository\MProcessDtoRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;

/**
 * @route("/user")
 */
class UserController extends AbstractGController
{
    public const DOMAINE = 'user';

    public function __construct(
        UserRepository $repository,
        UserManager $manager
    ) {
        $this->repository = $repository;
        $this->manager = $manager;
        $this->domaine = 'user';
    }

    /**
     * @Route("/", name="user_list", methods={"GET"})
     * @IsGranted("ROLE_USER")
     */
    public function list()
    {
        return $this->listAction();
    }

    /**
     * @Route("/add", name="user_add", methods={"GET","POST"})
     */
    public function add(Request $request)
    {
        return $this->editAction($request, new User(), UserType::class, false);
    }

    /**
     * @Route("/{id}", name="user_del", methods={"DELETE"})
     * @IsGranted("ROLE_GESTIONNAIRE")
     */
    public function delete(Request $request, User $item)
    {
        return $this->deleteAction($request, $item);
    }

    /**
     * @Route("/{id}", name="user_show", methods={"GET"})
     * @IsGranted("ROLE_USER")
     */
    public function show(
        User $item,
        MProcessDtoRepository $mProcessDtoRepository,
        ProcessDtoRepository $processDtoRepository
    ): Response {

        return $this->render('user/show.html.twig', [
            'item' => $item,
            'mps' => $mProcessDtoRepository->findAllForDto((new MProcessDto)->setIsEnable(MProcessDto::TRUE)),
            'ps' => $processDtoRepository->findAllForDto((new ProcessDto)->setVisible(ProcessDto::TRUE))
        ]);
    }

    /**
     * @Route("/{id}/edit", name="user_edit", methods={"GET","POST"})
     * @IsGranted("ROLE_GESTIONNAIRE")
     */
    public function edit(Request $request, User $item)
    {
        return $this->editAction($request, $item, UserType::class);
    }

    public function myWidgetNotificationAction(): Response
    {
        return $this->render('user/_myWidgetNotification.html.twig');
    }


    /**
     * @Route("/list/notifications", name="notification_users_subscription", methods={"GET"})
     * @IsGranted("ROLE_GESTIONNAIRE")
     */
    public function NotificationUsersSubscription(UserRepository $userRepository): Response
    {
        return $this->render(
            'user_notification/list.html.twig',
            ['items' => $userRepository->findAllUsersNotification()]
        );
    }

    /**
     * @Route("/list/notifications/p/{id}", name="notification_users_subscription_for_processus", methods={"GET"})
     * @IsGranted("ROLE_GESTIONNAIRE")
     */
    public function NotificationUsersSubscriptionForProcessus(UserRepository $userRepository, Process $process): Response
    {
        return $this->render(
            'user_notification/list_for_processus.html.twig',
            ['items' => $userRepository->findAllUsersNotificationForProcessus($process->getId()), 'item' => $process]
        );
    }

    /**
     * @Route("/list/notifications/mp/{id}", name="notification_users_subscription_for_mprocessus", methods={"GET"})
     * @IsGranted("ROLE_USER")
     */
    public function NotificationUsersSubscriptionFormProcessus(UserRepository $userRepository, MProcess $mprocess): Response
    {
        return $this->render(
            'user_notification/list_for_mprocessus.html.twig',
            ['items' => $userRepository->findAllUsersNotificationForMProcessus($mprocess->getId()), 'item' => $mprocess]
        );
    }

    /**
     * @Route("/list/subscription/mp/{id}", name="users_subscription_for_mprocessus", methods={"GET"})
     * @IsGranted("ROLE_USER")
     */
    public function UsersSubscriptionFormProcessus(UserRepository $userRepository, MProcess $mprocess): Response
    {
        return $this->render(
            'subscription/list_for_mprocessus.html.twig',
            ['items' => $userRepository->findAllUsersSubscriptionForMProcessus($mprocess->getId()), 'item' => $mprocess]
        );
    }
}
