<?php


namespace App\Tree;


use App\Entity\Backpack;
use App\Helper\ParamsInServices;
use App\Workflow\WorkflowData;

class BackpackTree extends AbstractTree
{
    /**
     * @var Backpack[]
     */
    protected $items;
    /**
     * @var Backpack
     */
    protected $item;

    private $hideState = false;

    private $sameMp = false;


    private $mprocess_last = '';
    private $mprocess_id = '';
    private $process_last = '';
    private $process_id = '';

    private $dir1_last = '';
    private $dir1_id = '';
    private $dir1_i = 0;
    private $dir2_last = '';
    private $dir2_id = '';
    private $dir2_i = 0;
    private $dir3_last = '';
    private $dir3_id = '';
    private $dir3_i = 0;
    private $dir4_last = '';
    private $dir4_id = '';
    private $dir4_i = 0;
    private $dir5_last = '';
    private $dir5_id = '';
    private $dir5_i = 0;


    private function check()
    {
    }

    /**
     * @param $path
     * @param string $parent
     * @param bool $baseFolderName
     *
     * @return array|null
     */
    public function getTree()
    {
        $this->getTreeCheck();
        foreach ($this->items as $item) {

            $open = $this->item->getId() === $item->getId();


            $this->GetBrancheMprocess($item);
            $this->GetBrancheProcess($item);
            $this->Dir1($item);
            $this->Dir2($item);
            $this->Dir3($item);
            $this->Dir4($item);
            $this->Dir5($item);




            $this->tree[] = [
                'id' => $item->getid(),
                'parent' => $this->getParent(),
                'text' => $this->getText($item),
                'icon' => $item->getCategory()->getIcone() . ' category_' . $item->getCategory()->getId(),
                'a_attr' => [
                    'href' => $this->generateUrl($item->getId()),
                ],
                'state' => [
                    'selected' => $open,
                    'opened' => true,
                ],
            ];
        }
        return json_encode($this->tree);
    }

    private function getText(Backpack $item)
    {
        $text = '';
        if (null !== $item->getRef()) {
            $text = $text . '<small class="muted">' . $item->getRef() . '</small> ';
        }
        $filesNumber = $item->getBackpackFiles()->count() + $item->getBackpackLinks()->count();
        $fileSpan = $filesNumber > 0 ? "&nbsp;<span class=\"small text-p-dark bg-p-light ml-2 pl-1 pr-1 rounded \"><i class=\"fas fa-paperclip\"></i> {$filesNumber}</span>&nbsp;" : '';

        return  $text . '<span class="text-p-dark">' . $item->getName() . '</span> ' . $fileSpan . $this->checkNews($item) . $this->checkState($item);
    }

    private function checkNews(Backpack $item): string
    {
        if (
            $item->getStateCurrent() === WorkflowData::STATE_PUBLISHED
            &&
            $this->getNbrDayBeetwenDates(new \DateTime(), $item->getUpdatedAt()) < $this->paramsInServices->get(ParamsInServices::ES_NEWS_TIME)
        ) {

            return '<i class="fas fa-certificate text-p-dark2 bg-p-light"></i>';
        }
        return '';
    }

    private function checkState(Backpack $item): string
    {
        if ($item->getStateCurrent() !== null && $this->hideState === false) {
            return "<span class='badge' style='background-color:" .
                WorkflowData::getBGColorOfState($item->getStateCurrent()) . 
                ";color:" . WorkflowData::getForeColorOfState($item->getStateCurrent()) .
                "'><i class='" . WorkflowData::getIconOfState($item->getStateCurrent()) . "'></i>" .
                 WorkflowData::getNameOfState($item->getStateCurrent()) . "</span>";
        }
        return '';
    }

    private function getNbrDayBeetwenDates(\DateTime $date1, \DateTime $date2)
    {

        $nbJoursTimestamp = $date1->getTimestamp() - $date2->getTimestamp();

        return round($nbJoursTimestamp / 86400);
    }

    protected function getParent()
    {
        if ($this->dir5_id != '') {
            return $this->dir5_id;
        }
        if ($this->dir4_id != '') {
            return $this->dir4_id;
        }
        if ($this->dir3_id != '') {
            return $this->dir3_id;
        }
        if ($this->dir2_id != '') {
            return $this->dir2_id;
        }
        if ($this->dir1_id != '') {
            return $this->dir1_id;
        }
        if ($this->process_id != '') {
            return $this->process_id;
        }
        return $this->mprocess_id;
    }


    private function GetBrancheMprocess(Backpack $backpack)
    {
        $data_courant = $backpack->getMProcess()->getFullName();
        $this->mprocess_id = 'mp_' . $backpack->getMProcess()->getid();

        if ($data_courant != $this->mprocess_last) {
            $parent =  '#';
            $this->addBranche($this->mprocess_id, $data_courant, $parent, $this->developed, $backpack->getMProcess()->getIsEnable(), 'fas fa-sitemap text-gray');
            $this->process_id = '';
            $this->process_last = '';
            $this->dir1_id = '';
            $this->dir1_last = '';
            $this->dir2_id = '';
            $this->dir2_last = '';
            $this->dir3_id = '';
            $this->dir3_last = '';
            $this->dir4_id = '';
            $this->dir4_last = '';
            $this->dir5_id = '';
            $this->dir5_last = '';
            $this->sameMp = false;
        } else {
            $this->sameMp = true;
        }

        $this->mprocess_last = $data_courant;
    }

    private function GetBrancheProcess(Backpack $backpack)
    {
        $data_courant = $backpack->getProcess();

        if (($data_courant === '' || $data_courant === null) && $this->sameMp) {
            return;
        }

        if (($data_courant === '' || $data_courant === null)) {
            $this->dir1_id = '';
            $this->dir1_last = '';
            $this->dir2_id = '';
            $this->dir2_last = '';
            $this->dir3_id = '';
            $this->dir3_last = '';
            $this->dir4_id = '';
            $this->dir4_last = '';
            $this->dir5_id = '';
            $this->dir5_last = '';
            return;
        }

        $data_courant = $backpack->getProcess()->getFullName();
        $this->process_id = 'p_' . $backpack->getProcess()->getid();

        if ($data_courant != $this->process_last) {
            $parent =  $this->mprocess_id;
            $this->addBranche($this->process_id, $data_courant, $parent, $this->developed, $backpack->getProcess()->getIsEnable(), 'fas fa-square text-gray ');

            $this->dir1_id = '';
            $this->dir1_last = '';
            $this->dir2_id = '';
            $this->dir2_last = '';
            $this->dir3_id = '';
            $this->dir3_last = '';
            $this->dir4_id = '';
            $this->dir4_last = '';
            $this->dir5_id = '';
            $this->dir5_last = '';
        }

        $this->process_last = $data_courant;
    }


    private function Dir1(Backpack $backpack)
    {
        $data_courant = $backpack->getDir1();

        if ($data_courant === '' || $data_courant === null) {
            $this->dir1_id = '';
            $this->dir1_last = '';
            $this->dir2_id = '';
            $this->dir2_last = '';
            $this->dir3_id = '';
            $this->dir3_last = '';
            $this->dir4_id = '';
            $this->dir4_last = '';
            $this->dir5_id = '';
            $this->dir5_last = '';
            return;
        }

        if ($data_courant != $this->dir1_last) {
            $this->dir1_i++;
            $this->dir1_id = 'd1_' . $this->dir1_i;

            $parent = $this->process_id ? $this->process_id : $this->mprocess_id;
            $this->addBranche($this->dir1_id, $data_courant, $parent, $this->developed);
        }
        $this->dir1_last = $data_courant;
    }

    private function Dir2(Backpack $backpack)
    {
        $data_courant = $backpack->getDir2();

        if ($data_courant === '' || $data_courant === null) {
            $this->dir2_id = '';
            $this->dir2_last = '';
            $this->dir3_id = '';
            $this->dir3_last = '';
            $this->dir4_id = '';
            $this->dir4_last = '';
            $this->dir5_id = '';
            $this->dir5_last = '';
            return;
        }

        if ($data_courant != $this->dir2_last) {
            $this->dir2_i++;
            $this->dir2_id = 'd2_' . $this->dir2_i;

            $parent = $this->dir1_id;
            $this->addBranche($this->dir2_id, $data_courant, $parent, $this->developed);

            $this->dir2_last = $data_courant;
        }
    }

    private function Dir3(Backpack $backpack)
    {
        $data_courant = $backpack->getDir3();

        if ($data_courant === '' || $data_courant === null) {
            $this->dir3_id = '';
            $this->dir3_last = '';
            $this->dir4_id = '';
            $this->dir4_last = '';
            $this->dir5_id = '';
            $this->dir5_last = '';
            return;
        }

        if ($data_courant != $this->dir3_last) {
            $this->dir3_i++;
            $this->dir3_id = 'd3_' . $this->dir3_i;

            $parent = $this->dir2_id;
            $this->addBranche($this->dir3_id, $data_courant, $parent, $this->developed);

            $this->dir3_last = $data_courant;
        }
    }

    private function Dir4(Backpack $backpack)
    {
        $data_courant = $backpack->getDir4();

        if ($data_courant === '' || $data_courant === null) {
            $this->dir4_id = '';
            $this->dir4_last = '';
            $this->dir5_id = '';
            $this->dir5_last = '';
            return;
        }

        if ($data_courant != $this->dir4_last) {
            $this->dir4_i++;
            $this->dir4_id = 'd4_' . $this->dir4_i;

            $parent = $this->dir3_id;
            $this->addBranche($this->dir4_id, $data_courant, $parent, $this->developed);

            $this->dir4_last = $data_courant;
        }
    }

    private function Dir5(Backpack $backpack)
    {
        $data_courant = $backpack->getDir5();

        if ($data_courant === '' || $data_courant === null) {
            $this->dir5_id = '';
            $this->dir5_last = '';
            return;
        }

        if ($data_courant != $this->dir5_last) {
            $this->dir5_i++;
            $this->dir5_id = 'd5_' . $this->dir5_i;

            $parent = $this->dir4_id;
            $this->addBranche($this->dir5_id, $data_courant, $parent, $this->developed);

            $this->dir5_last = $data_courant;
        }
    }

    public function hideThematic(): self
    {
        $this->hideThematic = true;
        $this->check();
        return $this;
    }

    public function hideRubric(): self
    {
        $this->hideRubric = true;
        $this->check();
        return $this;
    }

    public function hideUnderThematic(): self
    {
        $this->hideUnderThematic = true;
        $this->check();
        return $this;
    }

    public function hideState(): self
    {
        $this->hideState = true;
        return $this;
    }
}
