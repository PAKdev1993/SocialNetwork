<?php

namespace app\Table\MyCommunity\PendingContacts;

use app\Table\AppDisplay;
use core\Session\Session;

use app\Table\MyCommunity\PendingContacts\PendingContact\displayPendingContact;

class displayPendingContacts extends AppDisplay
{
    private $contactQis; // CETTE CLASSE A D'AVANTAGE BESOIN DES QUICK INFOS DE L'USER D'OU LE PASSAGE D'UN QI Object

    protected $pageName;

    public function __construct($contactsQis = false)
    {
        $this->pageName = 'community';
        parent::__construct(false, $this->pageName);
        $this->contactQis = $contactsQis;
    }

    public function showEdit()
    {
        
    }

    public function showBody()
    {
        $content = '';
        foreach($this->contactQis as $contactQi){
            $display = new displayPendingContact($contactQi);
            $content = $content . '<div class="community-elem col-md-12">'. $display->show() .'</div>';
        }
        return '<div class="pending-contact-body">' . $content .'</div>';
    }

    public function showEmptyPendingContact()
    {
        return '<div class="pending-contact-body">
                    <h1>'. $this->langFile[$this->pageName]->text_empty_pending_contacts .'</h1>
                </div>';
    }

    public function show()
    {
        if(Session::getInstance()->read('current-state')['state'] == 'owner')
        {
            if(!$this->contactQis)
            {
                return $this->showMyPendingContactsEmpty($this->showEmptyPendingContact());
            }
            else{
                return $this->showMyPendingContacts($this->showBody());
            }
        }
        if(Session::getInstance()->read('current-state')['state'] == 'viewer')
        {
            //return $this->showQuickInfos();
        }
    }
}