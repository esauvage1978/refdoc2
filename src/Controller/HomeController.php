<?php

declare(strict_types=1);

namespace App\Controller;

use App\Security\CurrentUser;
use App\Service\MakeDashboard;
use App\Service\BackpackCounter;
use App\Service\BackpackMakerDto;
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
    public function index(BackpackDtoRepository $backpackDtoRepository, CurrentUser $user)
    {
        $md = new MakeDashboard($backpackDtoRepository, $this->getUser());

        $dash_options = [
            $md->getData(BackpackMakerDto::DRAFT_UPDATABLE),
            $md->getData(BackpackMakerDto::MY_DRAFT_UPDATABLE),
            $md->getData(BackpackMakerDto::TO_RESUME_UPDATABLE),
            $md->getData(BackpackMakerDto::MY_TO_RESUME_UPDATABLE),
            $md->getData(BackpackMakerDto::TO_VALIDATE_UPDATABLE),
        ];

        if($user->isControl()) {
            $dash_options=array_merge($dash_options,[$md->getData(BackpackMakerDto::TO_CONTROL)]);
        }

        if ($user->isDoc()) {
            $dash_options = array_merge($dash_options, [$md->getData(BackpackMakerDto::TO_CHECK)]);
        }

        return $this->render('home/index.html.twig', ['dash_options' => $dash_options]);
    }

}
