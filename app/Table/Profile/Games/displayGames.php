<?php

namespace app\Table\Profile\Games;

use app\Table\AppDisplay;
use core\Session\Session;

use app\Table\Profile\Games\Game\displayGame;

class displayGames extends AppDisplay
{
    protected $pageName;

    private $games;

    public function __construct($games = false, $userToDisplay = false)
    {
        $this->pageName = 'Profile';
        parent::__construct($userToDisplay, $this->pageName);

        $this->games = $games;
    }

    public function showEdit()
    {
        return '<div class="bloc-edit-container">                               
                    <div class="title-edit-container">
                        <h1>'. $this->langFile[$this->pageName]->title_add_game .'</h1>
                    </div>
                    <div class="field-container col-md-6">
                        <div class="label-field">
                            '. $this->langFile[$this->pageName]->title_field_gameName_required .'
                        </div>
                        <div class="input min-large comment-input editable-content" id="add-game-name" contenteditable="true" placeholder="ex: Call of Duty 4, Battlefield"></div>
                        <div class="bulle-error">
                            <span class="message message-top"><span>'. $this->langFile[$this->pageName]->error_myprofile_gamer_career_game .'</span></span>
                            <span class="pseudo"></span>
                        </div>
                    </div>
                            
                    <div class="field-container col-md-6" id="logo-game-container">
                        <div class="label-field">
                            '. $this->langFile[$this->pageName]->title_field_logo_required .'
                        </div>
                        <div class="input files comment-input logo-input-container" data-elem="logo-input-container" id="logo-game-input-container" text="Select file">
                            <div class="loader-container little loader-profile-elem loader-profile-upload-logo" id="loader-logo-games">
                                <span class="loader loader-double">
                                </span>
                            </div>
                            <input name="pic" id="add-game-logo" data-elem="logo-input" accept="image/*" type="file">                          
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
                        <div class="input min-large comment-input editable-content" id="add-game-gameaccount" contenteditable="true" placeholder="ex: steam acount name"></div>
                        <div class="bulle-error">
                            <span class="message message-top"><span>'. $this->langFile[$this->pageName]->error_game_account_missing .'</span></span>
                            <span class="pseudo"></span>
                        </div>                       
                    </div> 
                                                      
                    <div class="field-container col-md-6">
                        <div class="label-field">
                            '. $this->langFile[$this->pageName]->title_field_myprofile_gamer_career_platform .'
                        </div>
                        <div class="select input comment-input">
                            <select name="platform" id="add-game-platform">
                                <option value=""> -- select one --</option>
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
                        <button class="share-button bt share-button-big" id="update-newgame-ft">'. $this->langFile[$this->pageName]->bt_myprofile_updatebutton .'</button>
                    </div>
                </div>';
    }

    public function showEditFt()
    {
        return '<div class="bloc-edit-container-permanent">                               
                    <div class="title-edit-container">
                        <h1>'. $this->langFile[$this->pageName]->title_add_game .'</h1>
                    </div>
                    <div class="field-container col-md-6">
                        <div class="label-field">
                           '. $this->langFile[$this->pageName]->title_field_gameName_required .'
                        </div>
                        <div class="input min-large comment-input editable-content" id="add-game-name" contenteditable="true" placeholder="ex: Call of Duty 4, Battlefield"></div>
                        <div class="bulle-error">
                            <span class="message message-top"><span>'. $this->langFile[$this->pageName]->error_myprofile_gamer_career_game .'</span></span>
                            <span class="pseudo"></span>
                        </div>
                    </div>
                            
                    <div class="field-container col-md-6">
                        <div class="label-field">
                            '. $this->langFile[$this->pageName]->title_field_logo_required .'
                        </div>
                        <div class="input files comment-input logo-input-container" id="logo-game-input-container" text="Select file">
                            <div class="loader-container little loader-profile-elem loader-profile-upload-logo" id="loader-logo-games">
                                <span class="loader loader-double">
                                </span>
                            </div>
                            <input name="pic" id="add-game-logo" accept="image/*" type="file">                          
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
                        <div class="input min-large comment-input editable-content" id="add-game-gameaccount" contenteditable="true" placeholder="ex: steam acount name"></div>
                        <div class="bulle-error">
                            <span class="message message-top"><span>'. $this->langFile[$this->pageName]->error_game_account_missing .'</span></span>
                            <span class="pseudo"></span>
                        </div>                       
                    </div> 
                                                      
                    <div class="field-container col-md-6">
                        <div class="label-field">
                            '. $this->langFile[$this->pageName]->title_field_myprofile_gamer_career_platform .'
                        </div>
                        <div class="select input comment-input">
                            <select name="platform" id="add-game-platform">
                                <option value=""> -- select one --</option>
                                <option value="PC">'. $this->langFile[$this->pageName]->title_field_myprofile_gamer_games_pc .'</option>
                                <option value="Console">'. $this->langFile[$this->pageName]->title_field_myprofile_gamer_games_console .'</option>                              
                            </select>
                        </div>
                         <div class="bulle-error">
                            <span class="message message-top"><span>'. $this->langFile[$this->pageName]->error_myprofile_gamer_career_platform .'</span></span>
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
                        <button class="share-button bt share-button-big" id="update-newgame-ft">'. $this->langFile[$this->pageName]->bt_myprofile_updatebutton .'</button>
                    </div>
                </div>';
    }

    public function showMyGamesBody()
    {
        $content = '';
        foreach($this->games as $game)
        {
            $display = new displayGame($game, $this->userToDisplay);
            $content = $content . $display->showMyGame();
        }
        return $content;
    }

    public function showGamesBody()
    {
        $content = '';
        foreach($this->games as $game)
        {
            $display = new displayGame($game, $this->userToDisplay);
            $content = $content . $display->showGame();
        }
        return $content;
    }

    public function showEmptyGames()
    {
        return '<div class="bloc-container empty">
                    <div class="message-empty-container">
                        <p><span class="bold">'. $this->userToDisplay->nickname .'</span> '. $this->langFile[$this->pageName]->title_myprofile_emptycontent_game .'</p>                       
                    </div>                    
                </div>';
    }

    public function show()
    {
        if(Session::getInstance()->read('current-state')['state'] == 'owner')
        {
            if(empty($this->games))
            {
                return $this->showMyGamesft($this->showEditFt());
            }
            else{
                return $this->showMyGames($this->showMyGamesBody());
            }
        }
        if(Session::getInstance()->read('current-state')['state'] == 'viewer')
        {
            if(empty($this->games))
            {
                return $this->showUserGamesEmpty($this->showEmptyGames());
            }
            else{
                return $this->showUserGames($this->showGamesBody());
            }
        }
    }
}