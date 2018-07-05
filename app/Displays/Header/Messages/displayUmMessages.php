<?php

namespace app\Displays\Header\Messages;

use app\Table\Messages\Conversations\displayConversations;

class displayUmMessages
{
    private $conversations;
    
    /**
     * displayUmMessages constructor.
     * @param $conversations conversationObject Array
     */
    public function __construct($conversations)
    {
        $this->conversations = $conversations;
    }

    public function showMessagesPart()
    {
        $display = new displayConversations($this->conversations, 'UmConversations');
        return $display->show();
    }

    public function show()
    {
        return $this->showMessagesPart();
    }
}