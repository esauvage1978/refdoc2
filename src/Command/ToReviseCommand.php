<?php

namespace App\Command;

use App\Dto\BackpackDto;
use App\Workflow\WorkflowData;
use App\Repository\BackpackDtoRepository;
use App\Workflow\WorkflowBackpackManager;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class ToReviseCommand extends Command
{
    protected static $defaultName = 'app:torevise';

    /**
     * @var BackpackDtoRepository
     */
    private $backpackDtoRepository;

    /**
     * @var WorkflowBackpackManager
     */
    private $workflow;

    public function __construct(BackpackDtoRepository $backpackDtoRepository, WorkflowBackpackManager $workflow)
    {
        parent::__construct();
        $this->backpackDtoRepository = $backpackDtoRepository;
        $this->workflow = $workflow;
    }

    protected function configure()
    {
        $this
            ->setDescription('bascule les porte-documents de l\'état publié à l\'état à réviser.')
            ->setHelp('.');
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $debut = microtime(true);


        $dto = new BackpackDto();
        $dto
            ->setStateCurrent(WorkflowData::STATE_PUBLISHED)
            ->setIsGoToRevise(BackpackDto::TRUE)
            ->setVisible(BackpackDto::TRUE);

        $datas = $this->backpackDtoRepository->findAllForDto($dto);

        $output->writeln(count($datas) . ' porte-documents à basculer ');

        foreach ($datas as $item) {
            $item->setStateContent('Modification avec la transition : ' . WorkflowData::TRANSITION_GO_TO_REVISE);

            $this->workflow->applyTransition($item, WorkflowData::TRANSITION_GO_TO_REVISE, 'Modification effectuée automatiquement',true);

            $output->writeln($item->getId() . ' : ' . $item->getStateAt()->format('Y-m-d'));
        }
        $fin = microtime(true);

        $output->writeln('Traitement effectué en  ' . $this->calculTime($fin, $debut) . ' millisecondes.');
        return 0;
    }




    private function calculTime($fin, $debut): int
    {
        return ($fin - $debut) * 1000;
    }
}
