<?php


namespace App\Workflow;


class WorkflowData
{
    const STATE_DRAFT = 'draft';

    public static function hasState(string $data): bool
    {
        $datas = [
            self::STATE_DRAFT
        ];

        if (in_array($data, $datas)) {
            return true;
        }
        throw new \InvalidArgumentException('cet état n\'existe pas : ' . $data);
    }
}
