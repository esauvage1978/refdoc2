<?php

namespace App\Controller;

use App\Controller\AbstractGController;
use App\Entity\Backpack;
use App\Repository\BackpackStateRepository;
use App\Workflow\WorkflowBackpackManager;
use App\Workflow\WorkflowData;
use DateTime;
use Exception;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/workflow")
 */
class WorkflowController extends AbstractGController
{

    /**
     * @Route("/{id}/check", name="workflow_action_check", methods={"GET","POST"})
     *
     * @param Action          $action
     * @param WorkflowActionManager $workflow
     *
     * @return Response
     *
     * @IsGranted("ROLE_USER")
     */
    public function checkAction(Backpack $backpack, WorkflowBackpackManager $workflow): Response
    {
        return $this->render('verif/workflow.html.twig', [
            'item' => $backpack,
        ]);
    }

    /**
     * @Route("/{id}/check/{transition}", name="workflow_backpack_check_apply_transition", methods={"GET","POST"})
     *
     * @param Backpack          $backpack
     * @param WorkflowBackpackManager $workflow
     *
     * @return Response
     *
     * @IsGranted("ROLE_USER")
     */
    public function checkApplyTransition(Backpack $backpack, WorkflowBackpackManager $workflow, string $transition): Response
    {
        $backpack->setStateContent('Modification avec la transition : ' . $transition);

        $workflow->applyTransition($backpack, $transition, 'Modification effectuée par l\'administrateur');

        return $this->redirectToRoute('workflow_backpack_check', ['id' => $backpack->getId()]);
    }


    /**
     * @Route("/{id}/history", name="workflow_backpack_history", methods={"GET"})
     *
     * @param BackpackStateRepository $repository
     * @param Backpack $backpack
     *
     * @return Response
     *
     * @IsGranted("ROLE_USER")
     */
    public function showHistoryBackpack(Backpack $backpack): Response
    {
        return $this->render('backpack/workflowHistory.html.twig', [
            'item' => $backpack
        ]);
    }


    /**
     * @Route("/{id}/notification", name="workflow_backpack_notification", methods={"GET"})
     *
     * @param BackpackStateRepository $repository
     * @param Backpack $backpack
     *
     * @return Response
     *
     * @IsGranted("ROLE_USER")
     */
    public function showNotificationBackpack(Backpack $backpack): Response
    {
        return $this->render('backpack/workflowNotification.html.twig', [
            'item' => $backpack
        ]);
    }

    /**
     * @Route("/{id}/{transition}", name="workflow_backpack_apply_transition", methods={"GET","POST"})
     *
     * @param Request $request
     * @param Backpack $item
     * @param WorkflowBackpackManager $workflowBackpackManager
     * @param string $transition
     *
     * @return Response
     *
     * @IsGranted("ROLE_USER")
     */
    public function applyTransitionBackpack(Request $request, Backpack $item, WorkflowBackpackManager $workflowBackpackManager, string $transition): Response
    {
        if (WorkflowData::hasTransition($transition)===false) {
            throw new Exception('transition non présente : ' . $transition);
        }

        if ($this->isCsrfTokenValid($transition . $item->getId(), $request->request->get('_token'))) {

            $content = $request->request->get($transition . '_content');

            $result = $workflowBackpackManager->applyTransition($item, $transition, $content);

            if ($result) {
                $this->addFlash(self::SUCCESS, 'Le changement d\'état est effectué');

                return $this->redirectToRoute('backpack_show', ['id' => $item->getId()]);
            }
            $this->addFlash(self::DANGER, 'Le changement d\'état n\'a pas abouti. Les conditions ne sont pas remplies.');
        }

        return $this->redirectToRoute('backpack_show', ['id' => $item->getId()]);
    }


}
