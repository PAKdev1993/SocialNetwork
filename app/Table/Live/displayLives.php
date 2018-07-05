<?php

namespace app\Table\Live;

use app\Table\AppDisplay;
use app\Table\Live\displayLive\displayLive;
use core\Session\Session;

class displayLives extends AppDisplay
{
    private $lives; //object array (home page), object (profile page)
    private $todisplay; //string: live profile, live home
    private $user; //user to display live

    protected $pageName;

    public function __construct($lives = false, $todisplay = false, $user = false)
    {
        $this->pageName = 'Profile';
        parent::__construct(false, $this->pageName);
        $this->lives = $lives;
        $this->todisplay = $todisplay;
        //user to display (not myself)
        $this->user = $user;
    }

    public function displayLiveEmpty()
    {
        //get user to display nickname
        return '<div class="bloc-container empty">
                    <div class="message-empty-container">
                        <p><span class="bold">'. $this->user->nickname .'</span> '. $this->langFile[$this->pageName]->message_live_user_not_available .'</p>
                    </div>                    
                </div>';
    }

    public function displayMyLive()
    {
        $display = new displayLive($this->lives, 'myProfileLive');
        return $display->show();
    }

    public function displayLive()
    {
        $display = new displayLive($this->lives, 'userProfileLive');
        return $display->show();
    }

    public function displayLiveEditFt()
    {
        return '<div class="bloc-edit-container-permanent">
                    <div class="loader-container loader-profile-elem loader-profile-elem-body" id="loader-live-body" data-elem="loader-live-body">
                        <div class="loader-double-container">
                            <span class="loader loader-double">
                            </span>
                        </div>                               
                    </div>                    
                    <div class="field-container col-md-12">
                        <div class="label-field info">
                            <p>'. $this->langFile[$this->pageName]->title_field_add_live .'</p>
                            <div class="label-info-img">
                                <img src="http://worldesport.com/public/img/infos/infosLive.jpg" alt="What i\'m suposed to do" style="opacity: 1;">
                            </div>
                        </div>
                        <div class="input descript comment-input share-bloc-text editable-content desc-input" placeholder="'. $this->langFile[$this->pageName]->placeholder_add_embeded_code_live .'" spellcheck="false" id="input-live-embeded" data-elem="add-summary-input" contenteditable="true"></div>
                        <div class="bulle-error">
                            <span class="message message-top"><span>'. $this->langFile[$this->pageName]->error_embeded_live_invalid .'</span></span>
                            <span class="pseudo"></span>
                        </div>
                    </div>

                    <div class="update-button-container">
                        <button class="share-button bt share-button-big" id="update-live" data-action="update-live">'. $this->langFile[$this->pageName]->bt_myprofile_updatebutton .'</button>
                    </div>
                </div>';
    }

    public function displayLiveEdit()
    {
        return '<div class="bloc-edit-container">
                    <div class="loader-container loader-profile-elem loader-profile-elem-body" id="loader-live-body" data-elem="loader-live-body">
                        <div class="loader-double-container">
                            <span class="loader loader-double">
                            </span>
                        </div>                               
                    </div>                    
                    <div class="field-container col-md-12">
                        <div class="label-field info">
                            '. $this->langFile[$this->pageName]->title_field_add_live .'
                            <div class="label-info-img">
                                <img src="http://worldesport.com/public/img/infos/infosLive.jpg" alt="What i\'m suposed to do" style="opacity: 1;">
                            </div>
                        </div>
                        <div class="input descript comment-input share-bloc-text editable-content desc-input" placeholder="'. $this->langFile[$this->pageName]->placeholder_add_embeded_code_live .'" spellcheck="false" id="input-live-embeded" data-elem="add-summary-input" contenteditable="true"></div>
                        <div class="bulle-error">
                            <span class="message message-top"><span>'. $this->langFile[$this->pageName]->error_embeded_live_invalid .'</span></span>
                            <span class="pseudo"></span>
                        </div>
                    </div>

                    <div class="update-button-container">
                        <button class="share-button bt share-button-big" id="update-live" data-action="update-live">'. $this->langFile[$this->pageName]->bt_myprofile_updatebutton .'</button>
                    </div>
                </div>';
    }

    public function show()
    {
        if (Session::getInstance()->read('current-state')['state'] == 'owner') 
        {
            if($this->todisplay == 'ProfileLive')
            {
                if (!$this->lives)
                {
                    return $this->showMyLiveEmpty($this->displayLiveEditFt());
                }
                else {
                    return $this->showMyLive($this->displayMyLive());
                }
            }
        }
        if (Session::getInstance()->read('current-state')['state'] == 'viewer')
        {
            if($this->todisplay == 'ProfileLive')
            {
                if (!$this->lives)
                {
                    return $this->showUserLiveEmpty($this->displayLiveEmpty());
                }
                else {
                    return $this->showUserLive($this->displayLive());
                }
            }
        }
    }
}