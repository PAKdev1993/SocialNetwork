<?php

namespace app\Table\MyCommunity\Contacts;

use app\Table\AppDisplay;
use core\Session\Session;
use core\Sort\Sort;

use app\Table\MyCommunity\Contacts\Contact\displayContact;

class displayContacts extends AppDisplay
{
    protected $pageName;

    private $contacts;

    public function __construct($contacts = false)
    {
        $this->pageName = 'community';
        parent::__construct(false, $this->pageName);
        $this->contacts = $contacts;
    }

    public function showEdit()
    {
        
    }

    public function showBody()
    {
        $sort = new Sort();
        $arrayAlphAssoc = $sort->createAlphSortFromUserElems($this->contacts);

        $content = '';
        foreach($arrayAlphAssoc as $alph => $contactArray)
        {
            //on créé le top jusqu'a l'ouverture du body-alph-container
            $top = '<div class="alph-bloc col-md-12" data-alph-index="'. $alph .'">
                        <div class="header-alph-container">
                            <div class="alph-title">
                                <div class="alph">
                                    <h1>'. $alph .'</h1>
                                </div>
                            </div>
                        </div>
                        <div class="body-alph-container">';

            //on insere dans le body alph container le contenu <=> les contacts displayed
            $contactContent = '';
            foreach($contactArray as $contact){
                $display = new displayContact($contact);
                $contactContent = $contactContent . '<div class="community-elem col-md-12">' . $display->show() . '</div>';
            }

            //on ferme le top et le alph-bloc
            $content = $content . $top . $contactContent .  '</div></div>';
        }
        return $content;
    }

    public function showEmptyContact()
    {
        return '<div class="contacts-body">
                    <h1>'. $this->langFile[$this->pageName]->text_empty_no_contacts .'</h1>
                </div>';
    }

    public function show()
    {
        if(Session::getInstance()->read('current-state')['state'] == 'owner')
        {
            if(empty($this->contacts))
            {
                return $this->showMyContactsEmpty($this->showEmptyContact());
            }
            else{
                return $this->showMyContacts($this->showBody());                
            }
        }
        if(Session::getInstance()->read('current-state')['state'] == 'viewer')
        {
            //return $this->showQuickInfos();
        }
    }
}