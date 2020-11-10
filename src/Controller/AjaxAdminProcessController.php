<?php

declare(strict_types=1);

namespace App\Controller;

use App\Dto\ProcessDto;
use App\Dto\MProcessDto;
use App\Repository\MProcessRepository;
use App\Repository\ProcessDtoRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;

class AjaxAdminProcessController extends AbstractGController
{

    /**
     * @Route("/ajax/getgrouping", name="ajax_fill_combobox_grouping", methods={"GET","POST"})
     *
     * @return Response
     * @IsGranted("ROLE_USER")
     */
    public function AjaxGetGrouping(Request $request, ProcessDtoRepository $processDtoRepository,MProcessRepository $mProcessRepository): Response
    {
        $pDto=new ProcessDto();
        $mpDto = new MProcessDto();
        $pDto->setMProcessDto($mpDto->setId($this->getMProcess($request)));

        return $this->json([
            'code' => 200,
            'value' => $processDtoRepository->findForComboboxGrouping($pDto),
            'message' => 'données transmises'
        ], 200);
    }

    private function getMProcess(Request $request)
    {
        return $request->request->has('mprocess') ? $request->request->get('mprocess') : null;
    }
}
