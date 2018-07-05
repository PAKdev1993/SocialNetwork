<?php

namespace core\MessageCenter;

use app\Table\Messages\Conversations\Conversation\displayConversation;
use app\Table\Messages\Conversations\ConversationModel;
use app\Table\Messages\Conversations\displayConversations;
use core\Session\Session;

class MessageCenterManager
{
    private $currentUser;

    private $conversations;

    public function __construct()
    {
        $this->currentUser = Session::getInstance()->read('auth');
    }

    public function getApercuConversations()
    {
        $model = new ConversationModel();
        $conversations = $model->getConvsByRange(0, 25);
        
        $display = new displayConversations($conversations, "apercuDiscutions");
        return $display->show();
    }

    public function getLastConversation()
    {
        $conversation = $this->conversations[0];
        $display = new displayConversation($conversation, "conv-message-center");
        return $display->show();
    }
    
    public function getConversation($idconv)
    {
        $model = new ConversationModel();
    }

    public function getNextMessagesFromOpenConversations()
    {
        //pour chaques openConv de la table openConv
    }
}