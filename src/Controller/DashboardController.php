<?php

declare(strict_types=1);

namespace App\Controller;

use App\Service\MakeDashboard;
use App\Service\BackpackCounter;
use App\Repository\BackpackDtoRepository;
use App\Service\BackpackMakerDto;
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
            $md->getData(BackpackMakerDto::DRAFT),
            $md->getData(BackpackMakerDto::DRAFT_UPDATABLE),
            $md->getData(BackpackMakerDto::MY_DRAFT_UPDATABLE),
        ];

        $abandonned = [
            $md->getData(BackpackMakerDto::ABANDONNED),
            $md->getData(BackpackMakerDto::ABANDONNED_UPDATABLE),
            $md->getData(BackpackMakerDto::MY_ABANDONNED_UPDATABLE),
        ];

        $toResume = [
            $md->getData(BackpackMakerDto::TO_RESUME),
            $md->getData(BackpackMakerDto::TO_RESUME_UPDATABLE),
            $md->getData(BackpackMakerDto::MY_TO_RESUME_UPDATABLE),
        ];

        $toValidate = [
            $md->getData(BackpackMakerDto::TO_VALIDATE),
            $md->getData(BackpackMakerDto::TO_VALIDATE_UPDATABLE),
            $md->getData(BackpackMakerDto::MY_TO_VALIDATE_UPDATABLE),
        ];

        $toControl = [
            $md->getData(BackpackMakerDto::TO_CONTROL),

        ];

        return $this->render(
            'dashboard/index.html.twig',
            [
                'draft' => $draft,
                'abandonned' => $abandonned,
                'toResume' => $toResume,
                'toValidate' => $toValidate,
                'toControl' => $toControl,
            ]
        );
    }
}
