<?php

namespace app\Table\Messages\Conversations;

use app\Table\AppDisplay;
use app\Table\Messages\Conversations\Conversation\displayConversation;
use core\MessageCenter\MessageCenterManager;
use core\Session\Session;

class displayConversations extends AppDisplay
{
    private $conversations;
    private $nbNotifsUm;
    protected $todisplay;   /*
                            *   chatBoxes : pour display une conversation et messages de chatBox                          
                            */

    protected $pageName;

    /**
     * displayConversations constructor.
     * @param object $conversations SI todisplay est umNotifications alors les conversations
     * @param bool $todisplay
     */
    public function __construct($conversations = false, $todisplay = false)
    {
        $this->pageName = 'messagecenter';
        parent::__construct(false, $this->pageName);
        $this->todisplay =          $todisplay;
        $this->conversations =      $conversations;
    }

    public function showEdit()
    {

    }
    
    public function showBody()
    {
        $content = '';
        $countNbNotifTotal = false;
        if($this->todisplay == 'UmConversations')
        {
            $countNbNotifTotal = true;
        }
        $nbTotalNotif = 0;
        foreach($this->conversations as $conversation)
        {
            $display = new displayConversation($conversation, $this->todisplay); //#todo OPTIMISER: ici on charge un affichage pour chaques user du tableau meme si celui ci a déjà été chargé
            $content = $content . $display->show();
            
            //compter le nb de nb notifs pour mettre a jour le nb total de notif du menu
            if($countNbNotifTotal)
            {
                $nbTotalNotif = $nbTotalNotif + (int) $display->notifInfos['nbNotifs'];
            }
        }
        $this->nbNotifsUm = $nbTotalNotif;

        return $content;
    }

    public function showEmpty()
    {
        return '<div class="bloc-container empty-recommended-container">
                     <div class="empty-title-container col-md-12">
                         <h1>'. $this->langFile[$this->pageName]->text_empty_notification .'</h1>
                     </div>   
                </div>';
    }

    public function showEmptyBodyUm()
    {
        return '<div class="empty-title-container col-md-12" data-elem="empty-notif">
                    <p>'. $this->langFileHeader->title_empty_notif .'</p>
               </div>';
    }

    public function showNotifBloc()
    {
        //ici le nb de notif est vide car il est calculé lor de l'affichage en JS
        return '<div class="nbnotif-container">
                    <div class="nb-notifs">
                       <p data-elem="unreadedNotifs">'. $this->nbNotifsUm .'</p>
                    </div>
                </div>';
    }

    public function show()
    {
        if(Session::getInstance()->read('current-state')['state'] == 'owner')
        {
            if($this->conversations)
            {
                if($this->todisplay == 'MessagePage')
                {
                    return $this->showMyNotifications($this->showBody());
                }
                if($this->todisplay == 'UmConversations')
                {
                    return $this->showMyMessagesUm($this->showBody(), $this->showNotifBloc());
                }
                if($this->todisplay == 'apercuDiscutions')
                {
                    return $this->showApercuDiscutions($this->showBody());
                }
            }
            else{
                if($this->todisplay == 'MessagePage')
                {
                    return $this->showMyNotificationsEmpty($this->showEmpty());
                }
                if($this->todisplay == 'UmConversations')
                {
                    return $this->showMyMessagesUmEmpty($this->showEmptyBodyUm());
                }
            }
        }
        if(Session::getInstance()->read('current-state')['state'] == 'viewer')
        {
            if($this->conversations)
            {
                if($this->todisplay == 'UmConversations')
                {
                    return $this->showMyMessagesUm($this->showBody(), $this->showNotifBloc());
                }
            }
            else{
                if($this->todisplay == 'UmConversations')
                {
                    return $this->showMyMessagesUmEmpty($this->showEmptyBodyUm());
                }
            }
        }
    }
}