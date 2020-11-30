<?php

namespace App\Controller;

use App\Dto\BackpackDto;
use App\Entity\Backpack;
use App\Manager\BackpackManager;
use App\Service\BackpackForTree;
use App\Service\BackpackMakerDto;
use App\Repository\BackpackRepository;
use App\Repository\BackpackDtoRepository;
use App\Service\BackpackCounter;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;

/**
 * Class ThematicController
 * @package App\Controller
 */
class BackpackTreeController extends AbstractGController
{


    /**
     * @var BackpackDtoRepository
     */
    private $backpackDtoRepository;

    /**
     * @var BackpackForTree
     */
    private $backpackForTree;

    public function __construct(
        BackpackRepository $repository,
        BackpackForTree $backpackForTree,
        BackpackDtoRepository $backpackDtoRepository
    ) {
        $this->repository = $repository;
        $this->domaine = 'backpack';
        $this->backpackForTree = $backpackForTree;
        $this->backpackDtoRepository= $backpackDtoRepository;
    }




    /**
     * @Route("/backpacks", name="backpacks", methods={"GET"})
     * @IsGranted("ROLE_USER")
     */
    public function treeView(Request $request, BackpackDto $dto)
    {
        $renderArray = $this->backpackForTree->getDatas($this->container, $request, null, $dto);
        return $this->render('backpack/tree.html.twig', $renderArray);
    }

    /**
     * @Route("/backpack/{id}/history", name="backpack_history", methods={"GET","POST"})
     * @return Response
     * @IsGranted("ROLE_USER")
     */
    public function history(Request $request, Backpack $item): Response
    {
        return $this->render('backpack/history.html.twig', [
            'item' => $item,
            'histories' => null
        ]);
    }


    public function widgetBackpackSubscriptionAction(): Response
    {
        $md = new BackpackCounter($this->backpackDtoRepository, $this->getUser());
        $nbr = $md->get(BackpackMakerDto::HOME_SUBSCRIPTION);
        return $this->render('backpack/_widgetBackpackSubscription.html.twig', ['nbr' => $nbr]);
    }

    public function widgetBackpacksAction(): Response
    {
        $md = new BackpackCounter($this->backpackDtoRepository, $this->getUser());
        $nbr = $md->get(BackpackMakerDto::DRAFT);
        return $this->render('backpack/_widgetBackpacks.html.twig', ['nbr' => $nbr]);
    }

    public function widgetBackpacksInProgressAction(): Response
    {
        $md = new BackpackCounter($this->backpackDtoRepository, $this->getUser());
        $nbr = $md->get(BackpackMakerDto::BACKPACK_IN_PROGRESS);
        return $this->render('backpack/_widgetBackpacksInProgress.html.twig', ['nbr' => $nbr]);
    }


    /**
     * @Route("/backpacks/in_progress", name="backpacks_in_progress", methods={"GET"})
     * @IsGranted("ROLE_USER")
     */
    public function backpacks_in_progress(Request $request)
    {
        $renderArray = $this->backpackForTree->getDatas($this->container, $request, BackpackMakerDto::BACKPACK_IN_PROGRESS);
        return $this->render('backpack/tree.html.twig', $renderArray);
    }

    /**
     * @Route("/backpacks/subscription", name="backpacks_subscription", methods={"GET"})
     * @IsGranted("ROLE_USER")
     */
    public function subscription(Request $request)
    {
        $renderArray = $this->backpackForTree->getDatas($this->container, $request, BackpackMakerDto::HOME_SUBSCRIPTION);
        return $this->render('backpack/tree.html.twig', $renderArray);
    }


    /**
     * @Route("/backpacks/draftupdatable", name="backpacks_draft_updatable", methods={"GET"})
     * @IsGranted("ROLE_USER")
     */
    public function state_draft_updatable(Request $request)
    {
        $renderArray = $this->backpackForTree->getDatas($this->container, $request, BackpackMakerDto::DRAFT_UPDATABLE);
        return $this->render('backpack/tree.html.twig', $renderArray);
    }


    /**
     * @Route("/backpacks/mydraftupdatable", name="backpacks_mydraft_updatable", methods={"GET"})
     * @IsGranted("ROLE_USER")
     */
    public function state_mydraft_updatable(Request $request)
    {
        $renderArray = $this->backpackForTree->getDatas($this->container, $request, BackpackMakerDto::MY_DRAFT_UPDATABLE);
        return $this->render('backpack/tree.html.twig', $renderArray);
    }


    /**
     * @Route("/backpacks/draft", name="backpacks_draft", methods={"GET"})
     * @IsGranted("ROLE_USER")
     */
    public function state_draft(Request $request)
    {
        $renderArray = $this->backpackForTree->getDatas($this->container, $request, BackpackMakerDto::DRAFT);
        return $this->render('backpack/tree.html.twig', $renderArray);
    }


    /**
     * @Route("/backpacks/abandonned", name="backpacks_abandonned", methods={"GET"})
     * @IsGranted("ROLE_USER")
     */
    public function state_abandonned(Request $request)
    {
        $renderArray = $this->backpackForTree->getDatas($this->container, $request, BackpackMakerDto::ABANDONNED);
        return $this->render('backpack/tree.html.twig', $renderArray);
    }
    /**
     * @Route("/backpacks/abandonnedupdatable", name="backpacks_abandonned_updatable", methods={"GET"})
     * @IsGranted("ROLE_USER")
     */
    public function state_abandonned_updatable(Request $request)
    {
        $renderArray = $this->backpackForTree->getDatas($this->container, $request, BackpackMakerDto::ABANDONNED_UPDATABLE);
        return $this->render('backpack/tree.html.twig', $renderArray);
    }

    /**
     * @Route("/backpacks/myabandonnedupdatable", name="backpacks_myabandonned_updatable", methods={"GET"})
     * @IsGranted("ROLE_USER")
     */
    public function state_myabandonned_updatable(Request $request)
    {
        $renderArray = $this->backpackForTree->getDatas($this->container, $request, BackpackMakerDto::MY_ABANDONNED_UPDATABLE);
        return $this->render('backpack/tree.html.twig', $renderArray);
    }


    /**
     * @Route("/backpacks/toresume", name="backpacks_toResume", methods={"GET"})
     * @IsGranted("ROLE_USER")
     */
    public function state_toresume(Request $request)
    {
        $renderArray = $this->backpackForTree->getDatas($this->container, $request, BackpackMakerDto::TO_RESUME);
        return $this->render('backpack/tree.html.twig', $renderArray);
    }
    /**
     * @Route("/backpacks/toresumeupdatable", name="backpacks_toResume_updatable", methods={"GET"})
     * @IsGranted("ROLE_USER")
     */
    public function state_toresume_updatable(Request $request)
    {
        $renderArray = $this->backpackForTree->getDatas($this->container, $request, BackpackMakerDto::TO_RESUME_UPDATABLE);
        return $this->render('backpack/tree.html.twig', $renderArray);
    }

    /**
     * @Route("/backpacks/mytoresumeupdatable", name="backpacks_mytoResume_updatable", methods={"GET"})
     * @IsGranted("ROLE_USER")
     */
    public function state_mytoresume_updatable(Request $request)
    {
        $renderArray = $this->backpackForTree->getDatas($this->container, $request, BackpackMakerDto::MY_TO_RESUME_UPDATABLE);
        return $this->render('backpack/tree.html.twig', $renderArray);
    }


    /**
     * @Route("/backpacks/tovalidate", name="backpacks_toValidate", methods={"GET"})
     * @IsGranted("ROLE_USER")
     */
    public function state_tovalidate(Request $request)
    {
        $renderArray = $this->backpackForTree->getDatas($this->container, $request, BackpackMakerDto::TO_VALIDATE);
        return $this->render('backpack/tree.html.twig', $renderArray);
    }
    /**
     * @Route("/backpacks/tovalidateupdatable", name="backpacks_toValidate_updatable", methods={"GET"})
     * @IsGranted("ROLE_USER")
     */
    public function state_tovalidate_updatable(Request $request)
    {
        $renderArray = $this->backpackForTree->getDatas($this->container, $request, BackpackMakerDto::TO_VALIDATE_UPDATABLE);
        return $this->render('backpack/tree.html.twig', $renderArray);
    }

    /**
     * @Route("/backpacks/mytovalidateupdatable", name="backpacks_mytoValidate_updatable", methods={"GET"})
     * @IsGranted("ROLE_USER")
     */
    public function state_mytovalidate_updatable(Request $request)
    {
        $renderArray = $this->backpackForTree->getDatas($this->container, $request, BackpackMakerDto::MY_TO_VALIDATE_UPDATABLE);
        return $this->render('backpack/tree.html.twig', $renderArray);
    }

    /**
     * @Route("/backpacks/tocontrol", name="backpacks_toControl", methods={"GET"})
     * @IsGranted("ROLE_USER")
     */
    public function state_tocontrol(Request $request)
    {
        $renderArray = $this->backpackForTree->getDatas($this->container, $request, BackpackMakerDto::TO_CONTROL);
        return $this->render('backpack/tree.html.twig', $renderArray);
    }

}
