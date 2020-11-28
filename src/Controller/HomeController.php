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

class HomeController extends AbstractController
{
    /**
     * @Route("/", name="home")
     * @IsGranted("ROLE_USER")
     */
    public function index(BackpackDtoRepository $backpackDtoRepository)
    {
        $md = new MakeDashboard($backpackDtoRepository, $this->getUser());

        $dash_options = [
            $md->getData(MakeDashboard::DRAFT_UPDATABLE),
            $md->getData(MakeDashboard::MY_DRAFT_UPDATABLE),
            $md->getData(MakeDashboard::TO_RESUME_UPDATABLE),
            $md->getData(MakeDashboard::MY_TO_RESUME_UPDATABLE),
            $md->getData(MakeDashboard::TO_VALIDATE_UPDATABLE),
        ];

        return $this->render('home/index.html.twig', ['dash_options' => $dash_options]);
    }

}
