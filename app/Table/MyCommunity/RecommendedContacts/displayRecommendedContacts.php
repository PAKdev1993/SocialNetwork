<?php

namespace app\Table\MyCommunity\RecommendedContacts;

use app\Table\AppDisplay;
use core\Session\Session;

use app\Table\MyCommunity\RecommendedContacts\RecommendedContact\displayRecommendedContact;

class displayRecommendedContacts extends AppDisplay
{
    private $recomdContacts;
    private $todisplay; // = false pour afficher le bloc de droite || = MyRecommendedContacts pour afficher la liste de 50 contacts
    protected $pageName;

    public function __construct($recommendedContacts = false, $todisplay = false)
    {
        $this->pageName = 'community';
        parent::__construct(false, $this->pageName);
        $this->todisplay = $todisplay;
        $this->recomdContacts = $recommendedContacts;
    }

    public function showEdit()
    {
    }

    public function showBody()
    {
        $content = '';
        foreach($this->recomdContacts as $recommendedContact)
        {
            $display = new displayRecommendedContact($recommendedContact);
            $content = $content . $display->show();
        }
        return $content;
    }

    public function showEmpty()
    {
        return '<div class="bloc-container empty-recommended-container">
                     <div class="empty-title-container col-md-12">
                         <h1>'. $this->langFile[$this->pageName]->text_empty_no_recommended_contact .'</h1>
                     </div>   
                </div>';
    }

    public function show()
    {
        if(Session::getInstance()->read('current-state')['state'] == 'owner')
        {
            if($this->recomdContacts)
            {
                if(!$this->todisplay)
                {
                    return $this->showMyRecommendedContactsRightBloc($this->showBody());
                }
                if($this->todisplay == 'MyRecommendedContacts')
                {
                    return $this->showMyRecommendedContacts($this->showBody());
                }
            }
            else{
                return $this->showMyRecommendedContactsEmpty($this->showEmpty());
            }

        }
        if(Session::getInstance()->read('current-state')['state'] == 'viewer')
        {
            if($this->recomdContacts)
            {
                if(!$this->todisplay)
                {
                    return $this->showMyRecommendedContactsRightBloc($this->showBody());
                }
                if($this->todisplay == 'MyRecommendedContacts')
                {
                    return $this->showMyRecommendedContacts($this->showBody());
                }
            }
            else{
                return $this->showMyRecommendedContactsEmpty($this->showEmpty());
            }
        }
    }
}