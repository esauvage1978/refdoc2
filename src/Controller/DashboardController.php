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

        $archived = [
            $md->getData(BackpackMakerDto::ARCHIVED),
            $md->getData(BackpackMakerDto::ARCHIVED_UPDATABLE),
            $md->getData(BackpackMakerDto::MY_ARCHIVED_UPDATABLE),
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

        $toCheck = [
            $md->getData(BackpackMakerDto::TO_CHECK),
        ];

        $published = [
            $md->getData(BackpackMakerDto::PUBLISHED),
            $md->getData(BackpackMakerDto::PUBLISHED_UPDATABLE),
            $md->getData(BackpackMakerDto::MY_PUBLISHED_UPDATABLE),
        ];


        $toRevise = [
            $md->getData(BackpackMakerDto::TO_REVISE),
            $md->getData(BackpackMakerDto::TO_REVISE_UPDATABLE),
            $md->getData(BackpackMakerDto::MY_TO_REVISE_UPDATABLE),
        ];

        $inReview = [
            $md->getData(BackpackMakerDto::IN_REVIEW),
            $md->getData(BackpackMakerDto::IN_REVIEW_UPDATABLE),
            $md->getData(BackpackMakerDto::MY_IN_REVIEW_UPDATABLE),
        ];

        $news = [
            $md->getData(BackpackMakerDto::HOME_NEWS),
            $md->getData(BackpackMakerDto::HOME_NEWS_SUBSCRIPTION),
        ];

        $goToRevise = [
            $md->getData(BackpackMakerDto::GO_TO_REVISE),
        ];

        return $this->render(
            'dashboard/index.html.twig',
            [
                'draft' => $draft,
                'abandonned' => $abandonned,
                'archived' => $archived,
                'toResume' => $toResume,
                'toValidate' => $toValidate,
                'toControl' => $toControl,
                'toCheck' => $toCheck,
                'published'=> $published,
                'news' => $news,
                'toRevise' => $toRevise,
                'goToRevise' => $goToRevise,
                'inReview' => $inReview,
            ]
        );
    }
}
