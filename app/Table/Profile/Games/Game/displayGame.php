<?php

namespace app\Table\Profile\Games\Game;

use app\Table\AppDisplayElem;
use app\Table\Profile\Equipments\displayEquipments;
use app\Table\Profile\Games\displayGames;
use core\Session\Session;

class displayGame extends displayGames
{
    //TEAM GAMES ATTRIBUTES
    private $id;
    private $name;
    private $gameaccount;
    private $platform;
    private $logo;

    //USER VARIABLES
    protected $userToDisplay;

    public function __construct($game, $userToDisplay = false)
    {
        parent::__construct();
        $this->id =             (empty($game->pk_idgame))    ? '' : $game->pk_idgame;
        $this->name =           (empty($game->name))         ? '' : $game->name;
        $this->gameaccount =    (empty($game->gameaccount))  ? '' : $game->gameaccount;
        $this->logo =           (empty($game->logo))         ? '' : $game->logo;
        $this->platform =       (empty($game->platform))     ? '' : $game->platform;

        //USER
        $this->userToDisplay = $userToDisplay ? $userToDisplay : Session::getInstance()->read('auth');
    }

    public function showEdit()
    {
        return '<div class="bloc-edit-container">
                    <div class="edit-ico-container">
                        <div class="close-edit close-edit-profile-bloc-elem"></div>
                    </div>
                    <div class="title-edit-container">
                        <h1>'. $this->langFile[$this->pageName]->word_edit .' '. $this->name .'</h1>
                    </div>
                    
                    <div class="field-container col-md-7">
                        <div class="label-field">
                           '. $this->langFile[$this->pageName]->title_field_gameName_required .'
                        </div>
                        <div class="input min-large comment-input editable-content" id="edit-game-name" contenteditable="true" placeholder="ex: Call of Duty 4, Battlefield">'. $this->name .'</div>
                        <div class="bulle-error">
                            <span class="message message-top"><span>'. $this->langFile[$this->pageName]->error_myprofile_gamer_career_game .'</span></span>
                            <span class="pseudo"></span>
                        </div>
                    </div>
                            
                    <div class="field-container col-md-7" id="logo-game-container">
                        <div class="label-field">
                            '. $this->langFile[$this->pageName]->title_field_logo_required .'
                        </div>
                        <div class="input files comment-input" data-elem="logo-input-container" text="Select file">
                            <div class="loader-container loader-profile-elem" id="loader-logo-games">
                                <div class="loader-double-container">
                                    <span class="loader loader-double">
                                    </span>
                                </div>
                            </div>
                            <input name="pic" id="edit-game-logo" data-elem="logo-input" accept="image/*" type="file">                           
                            <div class="bulle-error size-error">
                                <span class="message message-top"><span>'. $this->langErrorFiles->error_file_too_large .'</span></span>
                                <span class="pseudo"></span>
                            </div>
                            <div class="bulle-error ext-error">
                                <span class="message message-top"><span>'. $this->langErrorFiles->error_file_extension .'</span></span>
                                <span class="pseudo"></span>
                            </div>
                            <div class="bulle-error upload-error">
                                <span class="message message-top"><span>'. $this->langErrorFiles->error_upload .'</span></span>
                                <span class="pseudo"></span>
                            </div>
                            <div class="bulle-error error-type">
                                <span class="message message-top"><span>'. $this->langErrorFiles->error_mime_type .'</span></span>
                                <span class="pseudo"></span>
                            </div> 
                        </div>
                        <div class="bulle-error">
                            <span class="message message-top"><span>'. $this->langFile[$this->pageName]->error_myprofile_logo .'</span></span>
                            <span class="pseudo"></span>
                        </div>
                    </div>
                    
                    <div class="field-container col-md-6">
                        <div class="label-field">
                            '. $this->langFile[$this->pageName]->title_field_myprofile_gamer_games_gameaccount .'
                        </div>
                        <div class="input min-large comment-input editable-content" id="edit-game-gameaccount" contenteditable="true" placeholder="ex: steam acount name">'. $this->gameaccount .'</div>                       
                    </div> 
                                                      
                    <div class="field-container col-md-6">
                        <div class="label-field">
                            '. $this->langFile[$this->pageName]->title_field_myprofile_gamer_career_platform .'
                        </div>
                        <div class="select input comment-input">
                            <select name="platform" id="edit-game-platform">
                                <option value="'. $this->platform .'">'. $this->platform .'</option>
                                <option value="PC">'. $this->langFile[$this->pageName]->title_field_myprofile_gamer_games_pc .'</option>
                                <option value="Console">'. $this->langFile[$this->pageName]->title_field_myprofile_gamer_games_console .'</option>                              
                            </select>
                        </div>
                         <div class="bulle-error">
                            <span class="message message-top"><span>'. $this->langFile[$this->pageName]->error_myprofile_gamer_games_enterplatform .'</span></span>
                            <span class="pseudo"></span>
                        </div>
                    </div>
                 
                    <div class="update-button-container">
                        <div class="loader-container loader-bt" data-elem="loader-bt">
                            <div class="loader-double-container">
                                <span class="loader loader-double">
                                </span>
                            </div>
                        </div> 
                        <button class="share-button bt share-button-big" id="update-game">'. $this->langFile[$this->pageName]->bt_myprofile_updatebutton .'</button>
                    </div>
                </div>';
    }
    
    public function showMyGame()
    {
        //test de la platform
        switch ($this->platform){
            case 'PC':
                $img = '<img src="public/img/icon/computer_game_white.png" alt="WorldEsport logo">';
                break;
            case 'Console':
                $img = '<img src="public/img/icon/console_game_white.png" alt="WorldEsport logo">';
                break;
            default:
                $img = '';
        }
        
        return '<div class="profile-elem profil-game-container col-md-12" data-elem="'. $this->id .'">
                    <div class="profile-aside-container">
                        <div class="loader-container loader-profile-elem">
                            <div class="loader-double-container">
                                <span class="loader loader-double">
                                </span>
                            </div>
                        </div>
                        <div class="edit-container">
                            <div class="edit-ico-container">
                                <div class="edit-gear edit-profile-bloc-elem ico-gear"></div>                                
                            </div>
                            <div class="edit-options">
                           
                            </div>
                        </div>
                        <div class="profile-bloc-elem-left">
                            <div class="game-pic">
                                <img src="inc/img/imggames.php?imgname='. $this->logo .'&u='. $this->userToDisplay->pk_iduser .'" alt="'. $this->name .' logo">
                            </div>
                        </div>
                        <div class="profile-bloc-elem-right">
                            <div class="group-container col-md-12">
                                <div class="group-left-part col-md-9">
                                    <div class="infos-container col-md-12">
                                        <div class="info-line">
                                            <p class="info"><span class="bold">'. $this->langFile[$this->pageName]->title_game .'</span> '. $this->name .'</p>
                                        </div>
                                        <div class="info-line">
                                            <p class="info"><span class="bold">'. $this->langFile[$this->pageName]->title_account .'</span> '. $this->gameaccount .'</p>
                                        </div>
                                    </div>
                                </div>
                                <div class="group-right-part col-md-3">
                                    '. $img .' 
                                </div>
                            </div>
                        </div>
                    </div>
                </div>';
    }

    public function showGame()
    {
        //test de la platform
        switch ($this->platform){
            case 'PC':
                $img = '<img src="public/img/icon/computer_game_white.png" alt="WorldEsport logo">';
                break;
            case 'Console':
                $img = '<img src="public/img/icon/console_game_white.png" alt="WorldEsport logo">';
                break;
            default:
                $img = '';
        }

        return '<div class="profile-elem profil-game-container col-md-12" data-elem="'. $this->id .'">
                    <div class="profile-aside-container">                        
                        <div class="profile-bloc-elem-left">
                            <div class="game-pic">
                                <img src="inc/img/imggames.php?imgname='. $this->logo .'&u='. $this->userToDisplay->pk_iduser .'" alt="'. $this->name .' logo">
                            </div>
                        </div>
                        <div class="profile-bloc-elem-right">
                            <div class="group-container col-md-12">
                                <div class="group-left-part col-md-9">
                                    <div class="infos-container col-md-12">
                                        <div class="info-line">
                                            <p class="info"><span class="bold">'. $this->langFile[$this->pageName]->title_game .'</span> '. $this->name .'</p>
                                        </div>
                                        <div class="info-line">
                                            <p class="info"><span class="bold">'. $this->langFile[$this->pageName]->title_account .'</span> '. $this->gameaccount .'</p>
                                        </div>
                                    </div>
                                </div>
                                <div class="group-right-part col-md-3">
                                    '. $img .' 
                                </div>
                            </div>
                        </div>
                    </div>
                </div>';
    }

    public function show()
    {
        if(Session::getInstance()->read('current-state')['state'] == 'owner')
        {
            return $this->showMyGame();
        }
        if(Session::getInstance()->read('current-state')['state'] == 'viewer')
        {
            return $this->showGame();
        }
    }
}