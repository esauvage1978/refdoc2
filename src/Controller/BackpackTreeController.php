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




    public function widgetBackpackSubscriptionAction(): Response
    {
        $md = new BackpackCounter($this->backpackDtoRepository, $this->getUser());
        $nbr = $md->get(BackpackMakerDto::HOME_SUBSCRIPTION);
        return $this->render('backpack/_widgetBackpackSubscription.html.twig', ['nbr' => $nbr]);
    }

    public function widgetBackpacksAction(): Response
    {
        $md = new BackpackCounter($this->backpackDtoRepository, $this->getUser());
        $nbr = $md->get(BackpackMakerDto::BACKPACK_SHOW);
        return $this->render('backpack/_widgetBackpacks.html.twig', ['nbr' => $nbr]);
    }

    public function widgetBackpacksInProgressAction(): Response
    {
        $md = new BackpackCounter($this->backpackDtoRepository, $this->getUser());
        $nbr = $md->get(BackpackMakerDto::BACKPACK_IN_PROGRESS);
        return $this->render('backpack/_widgetBackpacksInProgress.html.twig', ['nbr' => $nbr]);
    }

    /**
     * @Route("/backpacks/show", name="backpacks_show", methods={"GET"})
     * @IsGranted("ROLE_USER")
     */
    public function backpacks_show(Request $request)
    {
        $renderArray = $this->backpackForTree->getDatas($this->container, $request, BackpackMakerDto::BACKPACK_SHOW);
        return $this->render('backpack/tree.html.twig', $renderArray);
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
     * @Route("/backpacks/yours", name="backpacks_yours", methods={"GET"})
     * @IsGranted("ROLE_USER")
     */
    public function backpacks_yours(Request $request)
    {
        $renderArray = $this->backpackForTree->getDatas($this->container, $request, BackpackMakerDto::BACKPACK_YOURS);
        return $this->render('backpack/tree.html.twig', $renderArray);
    }
    /**
     * @Route("/backpacks/go_to_revise", name="backpacks_goToRevise", methods={"GET"})
     * @IsGranted("ROLE_USER")
     */
    public function backpacks_go_to_revise(Request $request)
    {
        $renderArray = $this->backpackForTree->getDatas($this->container, $request, BackpackMakerDto::GO_TO_REVISE);
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
     * @Route("/backpacks/newssubscription", name="backpacks_news_subscription", methods={"GET"})
     * @IsGranted("ROLE_USER")
     */
    public function newsSubscription(Request $request)
    {
        $renderArray = $this->backpackForTree->getDatas($this->container, $request, BackpackMakerDto::HOME_NEWS_SUBSCRIPTION);
        return $this->render('backpack/tree.html.twig', $renderArray);
    }

    /**
     * @Route("/backpacks/news", name="backpacks_news", methods={"GET"})
     * @IsGranted("ROLE_USER")
     */
    public function news(Request $request)
    {
        $renderArray = $this->backpackForTree->getDatas($this->container, $request, BackpackMakerDto::HOME_NEWS);
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
     * @Route("/backpacks/archived", name="backpacks_archived", methods={"GET"})
     * @IsGranted("ROLE_USER")
     */
    public function state_archived(Request $request)
    {
        $renderArray = $this->backpackForTree->getDatas($this->container, $request, BackpackMakerDto::ARCHIVED);
        return $this->render('backpack/tree.html.twig', $renderArray);
    }
    /**
     * @Route("/backpacks/archivedupdatable", name="backpacks_archived_updatable", methods={"GET"})
     * @IsGranted("ROLE_USER")
     */
    public function state_archived_updatable(Request $request)
    {
        $renderArray = $this->backpackForTree->getDatas($this->container, $request, BackpackMakerDto::ARCHIVED_UPDATABLE);
        return $this->render('backpack/tree.html.twig', $renderArray);
    }

    /**
     * @Route("/backpacks/myarchivedupdatable", name="backpacks_myarchived_updatable", methods={"GET"})
     * @IsGranted("ROLE_USER")
     */
    public function state_myarchived_updatable(Request $request)
    {
        $renderArray = $this->backpackForTree->getDatas($this->container, $request, BackpackMakerDto::MY_ARCHIVED_UPDATABLE);
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

    /**
     * @Route("/backpacks/tocheck", name="backpacks_toCheck", methods={"GET"})
     * @IsGranted("ROLE_USER")
     */
    public function state_tocheck(Request $request)
    {
        $renderArray = $this->backpackForTree->getDatas($this->container, $request, BackpackMakerDto::TO_CHECK);
        return $this->render('backpack/tree.html.twig', $renderArray);
    }

    /**
     * @Route("/backpacks/published", name="backpacks_published", methods={"GET"})
     * @IsGranted("ROLE_USER")
     */
    public function state_published(Request $request)
    {
        $renderArray = $this->backpackForTree->getDatas($this->container, $request, BackpackMakerDto::PUBLISHED);
        return $this->render('backpack/tree.html.twig', $renderArray);
    }
    /**
     * @Route("/backpacks/publishedupdatable", name="backpacks_published_updatable", methods={"GET"})
     * @IsGranted("ROLE_USER")
     */
    public function state_published_updatable(Request $request)
    {
        $renderArray = $this->backpackForTree->getDatas($this->container, $request, BackpackMakerDto::PUBLISHED_UPDATABLE);
        return $this->render('backpack/tree.html.twig', $renderArray);
    }

    /**
     * @Route("/backpacks/mypublishedupdatable", name="backpacks_mypublished_updatable", methods={"GET"})
     * @IsGranted("ROLE_USER")
     */
    public function state_mypublished_updatable(Request $request)
    {
        $renderArray = $this->backpackForTree->getDatas($this->container, $request, BackpackMakerDto::MY_PUBLISHED_UPDATABLE);
        return $this->render('backpack/tree.html.twig', $renderArray);
    }

    /**
     * @Route("/backpacks/torevise", name="backpacks_toRevise", methods={"GET"})
     * @IsGranted("ROLE_USER")
     */
    public function state_torevise(Request $request)
    {
        $renderArray = $this->backpackForTree->getDatas($this->container, $request, BackpackMakerDto::TO_REVISE);
        return $this->render('backpack/tree.html.twig', $renderArray);
    }
    /**
     * @Route("/backpacks/toreviseupdatable", name="backpacks_toRevise_updatable", methods={"GET"})
     * @IsGranted("ROLE_USER")
     */
    public function state_torevise_updatable(Request $request)
    {
        $renderArray = $this->backpackForTree->getDatas($this->container, $request, BackpackMakerDto::TO_REVISE_UPDATABLE);
        return $this->render('backpack/tree.html.twig', $renderArray);
    }

    /**
     * @Route("/backpacks/mytoreviseupdatable", name="backpacks_mytoRevise_updatable", methods={"GET"})
     * @IsGranted("ROLE_USER")
     */
    public function state_mytorevise_updatable(Request $request)
    {
        $renderArray = $this->backpackForTree->getDatas($this->container, $request, BackpackMakerDto::MY_TO_REVISE_UPDATABLE);
        return $this->render('backpack/tree.html.twig', $renderArray);
    }

    /**
     * @Route("/backpacks/inreview", name="backpacks_inReview", methods={"GET"})
     * @IsGranted("ROLE_USER")
     */
    public function state_inreview(Request $request)
    {
        $renderArray = $this->backpackForTree->getDatas($this->container, $request, BackpackMakerDto::IN_REVIEW);
        return $this->render('backpack/tree.html.twig', $renderArray);
    }
    /**
     * @Route("/backpacks/inreviewupdatable", name="backpacks_inReview_updatable", methods={"GET"})
     * @IsGranted("ROLE_USER")
     */
    public function state_inreview_updatable(Request $request)
    {
        $renderArray = $this->backpackForTree->getDatas($this->container, $request, BackpackMakerDto::IN_REVIEW_UPDATABLE);
        return $this->render('backpack/tree.html.twig', $renderArray);
    }

    /**
     * @Route("/backpacks/myinreviewupdatable", name="backpacks_myinReview_updatable", methods={"GET"})
     * @IsGranted("ROLE_USER")
     */
    public function state_myinreview_updatable(Request $request)
    {
        $renderArray = $this->backpackForTree->getDatas($this->container, $request, BackpackMakerDto::MY_IN_REVIEW_UPDATABLE);
        return $this->render('backpack/tree.html.twig', $renderArray);
    }

     


}
