<?php

namespace app\Table\Live\displayLive;

use app\Table\Live\displayLives;
use core\Session\Session;

class displayLive extends displayLives
{
    private $id;
    private $iduser;
    private $embedHtml;
    private $online;
    private $date;

    private $todisplay; //string: live profile, live home

    function __construct($live = false, $todisplay)
    {
        parent::__construct();
        $this->id =             $live->pk_idlive    ? $live->pk_idlive  : false;
        $this->iduser =         $live->fk_iduser    ? $live->fk_iduser  : false;
        $this->embedHtml =      $live->embedhtml    ? $live->embedhtml  : false;
        $this->online =         $live->online       ? $live->online     : false;
        $this->date =           $live->date         ? $live->date       : false;
        $this->todisplay = $todisplay;
    }

    public function showLiveState()
    {
        if($this->online == 1)
        {
            return '<div class="block-status online">
                        <div class="left-part">

                        </div>
                        <div class="right-part" data-elem="status-message">
                            <p>'. $this->langGenerals->word_offline .'</p>
                            <p>'. $this->langGenerals->word_online .'</p>
                        </div>
                    </div>';
        }
        else{
            return '<div class="block-status offline">
                        <div class="left-part">

                        </div>
                        <div class="right-part" data-elem="status-message">
                            <p>'. $this->langGenerals->word_offline .'</p>
                            <p>'. $this->langGenerals->word_online .'</p>
                        </div>
                    </div>';
        }
    }

    public function showLiveControls()
    {
        return '<div class="live-bt-container">
                    <div class="post-live-button-container">
                        <button class="share-button bt share-button-big" id="post-live" data-action="post-live">'. $this->langFile[$this->pageName]->bt_post_live .'</button>
                        <div class="valid-click-bt">
                        </div>
                    </div>
                    <div class="stop-live-button-container">
                        <button class="share-button bt share-button-big" id="stop-live" data-action="stop-live"><p>'. $this->langFile[$this->pageName]->bt_stop_live .'</p></button>
                    </div>                           
                    <div class="post-live-input-container">
                        <div class="field-container">
                            <div class="input descript comment-input share-bloc-text editable-content desc-input" placeholder="'. $this->langFile[$this->pageName]->placeholder_share_live .'" spellcheck="false" id="input-post-live" data-elem="post-live-input" contenteditable="true"></div>
                            <div class="bulle-error">
                                <span class="message message-top"><span>'. $this->langFile[$this->pageName]->error_embeded_live_invalid .'</span></span>
                                <span class="pseudo"></span>
                            </div>
                        </div>                                
                    </div>                           
                </div>';
    }

    public function showLiveProfile()
    {
        if(!$this->online)
        {
            return '<div class="bloc-container" data-state="offline">
                        <div class="loader-container loader-profile-elem loader-profile-elem-body" id="loader-live-body" data-elem="loader-live-body">
                            <div class="loader-double-container">
                                <span class="loader loader-double">
                                </span>
                            </div>
                        </div>
                        '. $this->showLiveState() .'
                        <div class="mask-offline" data-action="put-live-on">
                            <p class="bt-play"> </p>
                            <p>'. $this->langFile[$this->pageName]->msg_notifiy_my_network_live .'</p>
                        </div>
                        <div class="embeded-container">
                            '. $this->embedHtml .'                       
                        </div>
                        '. $this->showLiveControls() .'                                       
                    </div>';
        }
        else{
            return '<div class="bloc-container" data-state="online">
                        <div class="loader-container loader-profile-elem loader-profile-elem-body" id="loader-live-body" data-elem="loader-live-body">
                            <div class="loader-double-container">
                                <span class="loader loader-double">
                                </span>
                            </div>
                        </div>
                        '. $this->showLiveState() .'
                        <div class="mask-offline" data-action="put-live-on">
                            <p class="bt-play"> </p>
                            <p>'. $this->langFile[$this->pageName]->msg_notifiy_my_network_live .'</p>
                        </div>
                        <div class="embeded-container">
                            '. $this->embedHtml .'                       
                        </div>                   
                        '. $this->showLiveControls() .'                        
                    </div>';
        }

    }

    public function showUserLiveProfile()
    {
        if(!$this->online)
        {
            return '<div class="bloc-container" data-state="offline">                        
                        '. $this->showLiveState() .'                        
                        <div class="embeded-container">
                            '. $this->embedHtml .'                       
                        </div>                                                             
                    </div>';
        }
        else{
            return '<div class="bloc-container" data-state="online">                        
                        '. $this->showLiveState() .'                       
                        <div class="embeded-container">
                            '. $this->embedHtml .'                       
                        </div>                                      
                    </div>';
        }
    }

    public function showBodyLiveHome()
    {
        
    }
    
    public function show()
    {
        if($this->todisplay == 'myProfileLive')
        {
            return $this->showLiveProfile();
        }
        if($this->todisplay == 'userProfileLive')
        {
            return $this->showUserLiveProfile();
        }
        if($this->todisplay == 'homeLive')
        {
            return $this->showLiveHome();
        }
        if($this->todisplay == 'postLive')
        {
            return $this->showPostLive();
        }
    }
}