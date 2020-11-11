<?php

declare(strict_types=1);

namespace App\Controller;

use App\Entity\Process;
use App\Entity\MProcess;
use App\Entity\Subscription;
use App\Security\CurrentUser;
use App\Repository\UserRepository;
use App\Manager\SubscriptionManager;
use App\Repository\SubscriptionRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class AjaxSubscriptionController extends AbstractGController
{

    /** @var User */
    private $user;

    /** @var SubscriptionRepository */
    private $subscriptionRepository;


    public function __construct(
        CurrentUser $currentUser,
        SubscriptionRepository $subscriptionRepository
    ) {
        $this->user = $currentUser->getUser();
        $this->subscriptionRepository = $subscriptionRepository;
    }
    
    /**
     * @Route("/ajax/toogle_abonnement_mp/{id}", name="ajax_toogle_abonnement_mp", methods={"GET","POST"})
     * @IsGranted("ROLE_USER")
     */
    public function AjaxToogleMp(
        Request $request,
        MProcess $mProcess,
        UserRepository $userRepository,
        SubscriptionManager $subscriptionManager
    ): Response {

        if ($this->user === null) {
            return $this->json([
                'code' => 403,
                'message' => 'Utilisateur non connecté'
            ], 403);
        }


        if ($request->query->has('u_id')) {
            $user = $userRepository->find($request->query->get('u_id'));
        } else {
            $user = $this->user;
        }


        $s = $this->subscriptionRepository->findOneBy(['user' => $user, 'mProcess' => $mProcess]);
        if (empty($s)) {
            $s = new Subscription();
            $s
                ->setUser($user)
                ->setMProcess($mProcess)
                ->setIsEnable(true)
                ->setCreatedAt(new \DateTime());
        } else {
            $s
                ->setIsEnable(!$s->getIsEnable())
                ->setModifyAt(new \DateTime());
        }

        $subscriptionManager->save($s);

        return $this->json([
            'code' => 200,
            'value' => $s->getIsEnable(),
            'message' => ($s->getIsEnable() ? 'Abonné' : 'Désabonné')
        ], 200);
    }

    /**
     * @Route("/ajax/toogle_abonnement_p/{id}", name="ajax_toogle_abonnement_p", methods={"GET","POST"})
     * @IsGranted("ROLE_USER")
     */
    public function AjaxToogleP(
        Request $request,
        Process $process,
        UserRepository $userRepository,
        SubscriptionManager $subscriptionManager
    ): Response {

        if ($this->user === null) {
            return $this->json([
                'code' => 403,
                'message' => 'Utilisateur non connecté'
            ], 403);
        }


        if ($request->query->has('u_id')) {
            $user = $userRepository->find($request->query->get('u_id'));
        } else {
            $user = $this->user;
        }


        $s = $this->subscriptionRepository->findOneBy(['user' => $user, 'process' => $process]);
        $return = true;
        if (empty($s)) {
            $s = new Subscription();
            $s
                ->setUser($user)
                ->setProcess($process)
                ->setIsEnable(true)
                ->setCreatedAt(new \DateTime());
        } else {
            $s
                ->setIsEnable(!$s->getIsEnable())
                ->setModifyAt(new \DateTime());
        }

        $subscriptionManager->save($s);

        return $this->json([
            'code' => 200,
            'value' => $s->getIsEnable(),
            'message' => ($s->getIsEnable() ? 'Abonné' : 'Désabonné')
        ], 200);
    }

}
