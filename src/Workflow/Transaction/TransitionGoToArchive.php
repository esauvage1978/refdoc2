<?php


namespace App\Workflow\Transaction;


class TransitionGoToArchive extends TransitionAbstract
{
    public function getExplains(): array
    {
        return [
            '<strong>Archiver</strong> ce porte-document.',
            'L\'archivage d\'un porte-document permet de le rendre opposable durant le temps de publication.',
            'Il peut s\'agir d\'une consigne qui a eu une période de validité et donc être utilisable pour justifier des pratiques auprès du service contrôle'
        ];
    }
}