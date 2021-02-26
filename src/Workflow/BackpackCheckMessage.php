<?php


namespace App\Workflow;


class BackpackCheckMessage
{
    /**
     * @var array
     */
    protected $messages=[
    ];

    /**
     * var bool
     */
    private $error;

    public function __construct()
    {
        $this->error=false;
    }

    public function addMessageSuccess(string $message)
    {
        array_push( $this->messages,
            [
                'type'=>'success',
                'message'=>$message
            ]
        );
    }

    public function addMessageError(string $message)
    {
        $this->error=true;
        array_push( $this->messages,
            [
                'type'=>'danger',
                'message'=>$message
            ]
        );
    }
    public function addMessageInformation(string $message)
    {
        array_push( $this->messages,
            [
                'type'=>'info',
                'message'=>$message
            ]
        );
    }

    public function hasMessageError(): bool
    {
        return $this->error;
    }


    public function getMessages(): array
    {
        return $this->messages;
    }

}