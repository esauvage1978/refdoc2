<?php

declare(strict_types=1);

namespace App\Controller;

use App\Dto\UserDto;
use App\Entity\User;
use App\Dto\ProcessDto;
use App\Dto\MProcessDto;
use App\Dto\SubscriptionDto;
use App\Security\CurrentUser;
use App\Repository\ProcessDtoRepository;
use App\Repository\MProcessDtoRepository;
use App\Repository\SubscriptionRepository;

use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class SubscriptionController extends AbstractController
{
    /** @var MProcessDtoRepository */
    private $MProcessDtoRepository;

    /** @var User */
    private $user;

    /** @var SubscriptionRepository */
    private $subscriptionRepository;

    /** @var ProcessDtoRepository */
    private $processDtoRepository;

    public function __construct(
        CurrentUser $currentUser,
        SubscriptionRepository $subscriptionRepository,
        MProcessDtoRepository $MProcessDtoRepository,
        ProcessDtoRepository $processDtoRepository
    ) {
        $this->MProcessDtoRepository = $MProcessDtoRepository;
        $this->user = $currentUser->getUser();
        $this->subscriptionRepository = $subscriptionRepository;
        $this->processDtoRepository = $processDtoRepository;
    }

    /**
     * @Route("/subscription", name="mySubscription")
     * @IsGranted("ROLE_USER")
     */
    public function mySubscription(): Response
    {
        return $this->render('subscription/list.html.twig', $this->getDatas($this->user));
    }

    /**
     * @Route("/subscription/{id}", name="subscription")
     * @IsGranted("ROLE_USER")
     */
    public function subscription(User $user): Response
    {
        return $this->render('subscription/list.html.twig', $this->getDatas($user, true));
    }

    public function myListForHomeAction(): Response
    {
        return $this->render('subscription/_mylistforhome.html.twig', $this->getDatas($this->user));
    }

    public function myWidgetAction(): Response
    {
        return $this->render('subscription/_myWidget.html.twig', $this->getDatas($this->user));
    }
 
    private function getDatas(User $user, bool $admin = false): array
    {
        $dto_MP = new MProcessDto();
        $dto_P = new ProcessDto();
        $dtoU = new UserDto();
        $dtoS = new SubscriptionDto();

        $dto_MP->setIsEnable(MProcessDto::TRUE);
        $items = $this->MProcessDtoRepository->findAllForDto($dto_MP);

        $dtoU->setId($user->getId());
        $dtoS->setUserDto($dtoU);
        $dto_MP->setSubscriptionDto($dtoS);
        $abosMP = $this->MProcessDtoRepository->findAllForDto($dto_MP, MProcessDtoRepository::FILTRE_DTO_INIT_SUBSCRIPTION);

        $dto_P->setSubscriptionDto($dtoS);
        $abosP = $this->processDtoRepository->findAllForDto($dto_P, MProcessDtoRepository::FILTRE_DTO_INIT_SUBSCRIPTION);
        
        return [
            'items' => $items,
            'abosMP' => $abosMP,
            'abosP' => $abosP,
            'user' => $user,
            'admin' => $admin
        ];
    }
}
