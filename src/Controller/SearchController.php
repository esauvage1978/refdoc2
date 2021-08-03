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
class SearchController extends AbstractGController
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
        $this->backpackDtoRepository = $backpackDtoRepository;
    }


    /**
     * @return Response
     */
    public function searchFormAction(): Response
    {
        return $this->render('home/search-form.html.twig', []);
    }

    /**
     * @Route("/search", name="search", methods={"GET","POST"})
     * @IsGranted("ROLE_USER")
     */
    public function search(Request $request)
    {
        $r = $request->get('r');
        $show_all = $request->get('show_all');
        if ($r === null || empty($r)) {
            return $this->redirectToRoute('home');
        } else {

            $dto = $this->backpackForTree->getDto(BackpackMakerDto::SEARCH, $r);
            if (null === $show_all) {
                $dto->setIsShow(BackpackDto::TRUE)
                    ->setVisible(BackpackDto::TRUE);
            }
            $renderArray = $this->backpackForTree->getDatas($this->container, $request, null, $dto);
            $renderArray = array_merge(
                $renderArray,
                [
                    'word' => $r,
                    'show_all'=>$show_all
                ]
            );
            return $this->render('backpack/tree_search.html.twig', $renderArray);
        }
    }
}
