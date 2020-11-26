<?php

declare(strict_types=1);

namespace App\Controller;

use App\Service\MakeDashboard;
use App\Service\BackpackCounter;
use App\Repository\BackpackDtoRepository;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class DashboardController extends AbstractController
{
    /**
     * @Route("/dashboard", name="dashboard")
     * @IsGranted("ROLE_USER")
     */
    public function index(BackpackDtoRepository $backpackDtoRepository)
    {
        $md = new MakeDashboard($backpackDtoRepository, $this->getUser());

        $draft = [
            $md->getData(MakeDashboard::DRAFT),
            $md->getData(MakeDashboard::DRAFT_UPDATABLE),
            $md->getData(MakeDashboard::MY_DRAFT_UPDATABLE),
        ];

        $abandonned = [
            $md->getData(MakeDashboard::ABANDONNED),
            $md->getData(MakeDashboard::ABANDONNED_UPDATABLE),
            $md->getData(MakeDashboard::MY_ABANDONNED_UPDATABLE),
        ];

        $toResume = [
            $md->getData(MakeDashboard::TO_RESUME),
            $md->getData(MakeDashboard::TO_RESUME_UPDATABLE),
            $md->getData(MakeDashboard::MY_TO_RESUME_UPDATABLE),
        ];

        return $this->render(
            'dashboard/index.html.twig',
            [
                'draft' => $draft,
                'abandonned' => $abandonned,
                'toResume' => $toResume,
            ]
        );
    }
}