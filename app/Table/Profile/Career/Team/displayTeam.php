<?php

namespace app\Table\Profile\Career\Team;

use app\Table\AppDisplayElem;
use app\Table\Profile\Career\displayCareer;
use core\Session\Session;

class displayTeam extends displayCareer
{
    //TEAM ATTRIBUTES
    private $id;
    private $name;
    private $logo;
    private $game;
    private $role;
    private $startdate;
    private $enddate;
    private $platform;
    private $description;
    private $curplayhere;

    //DATE VARIABLES
    private $monthStart = 'mm';
    private $yearStart = 'yyyy';
    private $monthEnd = 'mm';
    private $yearEnd = 'yyyy';

    private $monthStartValue;
    private $monthEndValue;

    //USER VARIABLES
    protected $userToDisplay;

    public function __construct($team, $userToDisplay = false)
    {
        parent::__construct();
        $this->id =             (empty($team->pk_idteam))   ? '' : $team->pk_idteam;
        $this->name =           (empty($team->name))        ? '' : $team->name;
        $this->logo =           (empty($team->logo))        ? '' : $team->logo;
        $this->game =           (empty($team->game))        ? '' : $team->game;
        $this->role =           (empty($team->role))        ? '' : $team->role;
        $this->startdate =      (empty($team->startdate))   ? '' : $team->startdate;
        $this->enddate =        (empty($team->enddate))     ? '' : $team->enddate;
        $this->platform =       (empty($team->platform))    ? '' : $team->platform;
        $this->description =    (empty($team->description)) ? '' : $team->description;
        $this->curplayhere =    (empty($team->currentplayhere)) ? '' : $team->currentplayhere;

        if($this->id)
        {
            //USER
            $this->userToDisplay = $userToDisplay ? $userToDisplay : Session::getInstance()->read('auth');

            //transform startDate to insert in form
            $dateFormated = date("d-m-Y", strtotime($this->startdate));
            $pieces = explode('-', $dateFormated);
            $this->monthStartValue = $pieces[1];
            $this->yearStart = $pieces[2];

            $corresMonth = (int) ltrim($this->monthStartValue, '0');
            $monthName = $this->montharray[(int)$corresMonth];
            $this->monthStart = $this->monthTraduce->$monthName;

            //transform endDate to insert in form
            $dateFormated = date("d-m-Y", strtotime($this->enddate));
            $pieces = explode('-', $dateFormated);
            $this->monthEndValue = $pieces[1];
            $this->yearEnd = $pieces[2];

            $corresMonth = (int) ltrim($this->monthEndValue, '0');
            $monthName = $this->montharray[(int)$corresMonth];
            $this->monthEnd = $this->monthTraduce->$monthName;
        }
    }
    
    public function showEdit()
    {
        $statecurplayhere = "";
        if($this->curplayhere != 0)
        {
            $statecurplayhere = 'checked';

            $enddatefield = '<div class="field-container hided-field col-md-6" id="enddate-field">
                                    <div class="label-field">
                                        '. $this->langFile[$this->pageName]->title_field_myprofile_employee_enddate .'
                                    </div>
                                    <div class="select-date month input comment-input col-md-2">
                                        <select name="edit-team-end-month" id="edit-team-end-month">
                                            <option value="'. $this->monthEndValue .'">'. $this->monthEnd .'</option>
                                             <option value="01">'. $this->monthTraduce->January .'</option>
                                            <option value="02">'. $this->monthTraduce->February .'</option>
                                            <option value="03">'. $this->monthTraduce->March .'</option>
                                            <option value="04">'. $this->monthTraduce->April .'</option>
                                            <option value="05">'. $this->monthTraduce->May .'</option>
                                            <option value="06">'. $this->monthTraduce->June .'</option>
                                            <option value="07">'. $this->monthTraduce->July .'</option>
                                            <option value="08">'. $this->monthTraduce->August .'</option>
                                            <option value="09">'. $this->monthTraduce->September .'</option>
                                            <option value="10">'. $this->monthTraduce->October .'</option>
                                            <option value="11">'. $this->monthTraduce->November .'</option>
                                            <option value="12">'. $this->monthTraduce->December .'</option>
                                        </select>
                                    </div>
                                    <div class="bulle-error-special">
                                        <span class="message message-top"><span>'. $this->langFile[$this->pageName]->error_myprofile_incorrectdates .'</span></span>
                                        <span class="pseudo"></span>
                                    </div> 
                                    <div class="select-date year input comment-input col-md-2">
                                        <select name="edit-team-end-year" id="edit-team-end-year">
                                            <option value="'. $this->yearEnd .'">'. $this->yearEnd .'</option>
                                            '. $this->formElems->selectYearValues() .'
                                        </select>
                                    </div>
                                    <div class="bulle-error">
                                        <span class="message message-top"><span>Put valid date please :)</span></span>
                                        <span class="pseudo"></span>
                                    </div>
                                </div>';
        }
        else{
            $enddatefield = '<div class="field-container col-md-6 enddate" id="enddate-field">
                                    <div class="label-field">
                                        '. $this->langFile[$this->pageName]->title_field_myprofile_employee_enddate .'
                                    </div>
                                    <div class="select-date month input comment-input col-md-2">
                                        <select name="edit-team-end-month" id="edit-team-end-month">
                                            <option value="'. $this->monthEndValue .'">'. $this->monthEnd .'</option>
                                            <option value="01">'. $this->monthTraduce->January .'</option>
                                            <option value="02">'. $this->monthTraduce->February .'</option>
                                            <option value="03">'. $this->monthTraduce->March .'</option>
                                            <option value="04">'. $this->monthTraduce->April .'</option>
                                            <option value="05">'. $this->monthTraduce->May .'</option>
                                            <option value="06">'. $this->monthTraduce->June .'</option>
                                            <option value="07">'. $this->monthTraduce->July .'</option>
                                            <option value="08">'. $this->monthTraduce->August .'</option>
                                            <option value="09">'. $this->monthTraduce->September .'</option>
                                            <option value="10">'. $this->monthTraduce->October .'</option>
                                            <option value="11">'. $this->monthTraduce->November .'</option>
                                            <option value="12">'. $this->monthTraduce->December .'</option>
                                        </select>
                                    </div>
                                    <div class="bulle-error-special">
                                        <span class="message message-top"><span>'. $this->langFile[$this->pageName]->error_myprofile_incorrectdates .'</span></span>
                                        <span class="pseudo"></span>
                                    </div> 
                                    <div class="select-date year input comment-input col-md-2">
                                        <select name="edit-team-end-year" id="edit-team-end-year">
                                            <option value="'. $this->yearEnd .'">'. $this->yearEnd .'</option>
                                            '. $this->formElems->selectYearValues() .'
                                        </select>
                                    </div>
                                    <div class="bulle-error">
                                        <span class="message message-top"><span>'. $this->langFile[$this->pageName]->error_myprofile_entervaliddate .'</span></span>
                                        <span class="pseudo"></span>
                                    </div>
                                </div>';
        }

        return '<div class="bloc-edit-container">
                    <div class="edit-ico-container">
                        <div class="close-edit close-edit-profile-bloc-elem"></div>
                    </div>
                    <div class="title-edit-container">
                        <h1>'. $this->langFile[$this->pageName]->word_edit .' '. $this->name .'</h1>
                    </div>
                         
                    <div class="field-container col-md-8">
                        <div class="label-field">
                            '. $this->langFile[$this->pageName]->title_field_myprofile_gamer_career_teamname .'
                        </div>
                        <div class="input min-large comment-input editable-content" placeholder="ex: H2k, NIP" contenteditable="true" spellcheck="false" id="edit-team-teamname">'. $this->name .'</div>
                        <div class="bulle-error">
                            <span class="message message-top"><span>'. $this->langFile[$this->pageName]->error_myprofile_name .'</span></span>
                            <span class="pseudo"></span>
                        </div> 
                    </div>
                         
                    <div class="field-container col-md-8">
                        <div class="label-field">
                            '. $this->langFile[$this->pageName]->title_field_myprofile_employee_logo .'
                        </div>
                        <div class="input files comment-input" data-elem="logo-input-container" text="Select file">
                            <div class="loader-container little loader-profile-elem loader-profile-upload-logo" id="loader-logo-team">
                                <span class="loader loader-double">
                                </span>
                            </div>
                            <input name="pic" data-elem="logo-input" accept="image/*" type="file" id="edit-team-logoteam">
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
                    </div>

                    <div class="field-container col-md-6">
                        <div class="label-field">
                            '. $this->langFile[$this->pageName]->title_field_myprofile_gamer_career_game .'
                        </div>
                        <div class="input min-large comment-input editable-content" placeholder="ex: Call of Duty 4 ..." contenteditable="true" spellcheck="false" id="edit-team-game">'. $this->game .'</div>
                        <div class="bulle-error">
                            <span class="message message-top"><span>'. $this->langFile[$this->pageName]->error_myprofile_gamer_career_game .'</span></span>
                            <span class="pseudo"></span>
                        </div>
                    </div>

                    <div class="field-container col-md-6">
                        <div class="label-field">
                            '. $this->langFile[$this->pageName]->title_field_myprofile_gamer_career_platform .'
                        </div>
                        <div class="input min-large comment-input editable-content" placeholder="ex: PC, XBOX ONE ..."contenteditable="true" spellcheck="false" id="edit-team-platform">'. $this->platform .'</div>
                        <div class="bulle-error">
                            <span class="message message-top"><span>'. $this->langFile[$this->pageName]->error_myprofile_gamer_career_platform .'</span></span>
                            <span class="pseudo"></span>
                        </div>
                    </div>

                    <div class="field-container col-md-8">
                        <div class="label-field">
                            '. $this->langFile[$this->pageName]->title_field_myprofile_gamer_career_role .'
                        </div>
                        <div class="input min-large comment-input editable-content" placeholder="ex: AWP, AK ..." contenteditable="true" spellcheck="false" id="edit-team-role">'. $this->role .'</div>
                        <div class="bulle-error">
                            <span class="message message-top"><span>'. $this->langFile[$this->pageName]->error_myprofile_gamer_career_role .'</span></span>
                            <span class="pseudo"></span>
                        </div>
                    </div>

                    <div class="field-container col-md-6">
                        <div class="label-field">
                            '. $this->langFile[$this->pageName]->title_field_myprofile_employee_startdate .'
                        </div>
                        <div class="select-date month input comment-input col-md-2">
                            <select name="edit-team-start-month" id="edit-team-start-month">
                                <option value="'. $this->monthStartValue .'">'. $this->monthStart .'</option>
                                <option value="01">'. $this->monthTraduce->January .'</option>
                                <option value="02">'. $this->monthTraduce->February .'</option>
                                <option value="03">'. $this->monthTraduce->March .'</option>
                                <option value="04">'. $this->monthTraduce->April .'</option>
                                <option value="05">'. $this->monthTraduce->May .'</option>
                                <option value="06">'. $this->monthTraduce->June .'</option>
                                <option value="07">'. $this->monthTraduce->July .'</option>
                                <option value="08">'. $this->monthTraduce->August .'</option>
                                <option value="09">'. $this->monthTraduce->September .'</option>
                                <option value="10">'. $this->monthTraduce->October .'</option>
                                <option value="11">'. $this->monthTraduce->November .'</option>
                                <option value="12">'. $this->monthTraduce->December .'</option>
                            </select>
                        </div>
                        <div class="select-date year input comment-input col-md-2">
                            <select name="edit-team-start-year" id="edit-team-start-year">
                                <option value="'. $this->yearStart .'">'. $this->yearStart .'</option>
                                '. $this->formElems->selectYearValues() .'
                            </select>
                        </div>
                        <div class="bulle-error">
                            <span class="message message-top"><span>'. $this->langFile[$this->pageName]->error_myprofile_entervaliddate .'</span></span>
                            <span class="pseudo"></span>
                        </div>
                    </div>

                   '. $enddatefield .'

                    <div class="field-container col-md-12">
                        <input name="currently-played" value="" class="input" id="edit-team-current-activity" type="checkbox" '. $statecurplayhere .'>
                        <label for="edit-team-current-activity">'. $this->langFile[$this->pageName]->title_field_myprofile_gamer_currentlyplay .'</label>
                    </div>

                    <div class="field-container col-md-12">
                        <div class="label-field">
                            '. $this->langFile[$this->pageName]->title_field_description .'
                        </div>
                        <div class="input descript comment-input share-bloc-text editable-content desc-input" placeholder="'. $this->langFile[$this->pageName]->placeholder_give_more_details .'" spellcheck="false" contenteditable="true" id="edit-team-decript" spellcheck="false">'. $this->description .'</div>
                    </div>

                    <div class="update-button-container">
                        <div class="loader-container loader-bt" data-elem="loader-bt">
                            <div class="loader-double-container">
                                <span class="loader loader-double">
                                </span>
                            </div>
                        </div> 
                        <button class="share-button bt share-button-big" id="update-team">'. $this->langFile[$this->pageName]->bt_myprofile_updatebutton .'</button>
                    </div>
                </div>';
    }

    public function showMyTeam()
    {
        //test du logo: default ou non
        switch($this->logo){
            case 'default':
                $logo = '<img src="public/img/default/team.png" alt="WorldEsport Team logo">';
                break;
            default:
                $logo = '<img src="inc/img/imgteams.php?imgname='. $this->logo .'&u='. $this->userToDisplay->pk_iduser .'" alt="WorldEsport Team logo">'; //#todo AMBIGUE: homogeiniser les paramètres envoyés aux images ca ne peux pas continuer comme ca
        }

        //test du currentplayhere, false or true
        switch($this->curplayhere){
            case true:
                $currentPlayHereContent = ' <div class="info-line current-work">
                                                <p>'. $this->langFile[$this->pageName]->title_field_myprofile_gamer_currentlyplay .'</p>
                                            </div>';
                $currentPlayerTime = $this->langFile[$this->pageName]->word_now;
                $class = "";
                break;
            default:
                $currentPlayHereContent = '';
                $currentPlayerTime = $this->monthEnd .' '.$this->yearEnd;
                $class = "o-hidden";
        }

        //test de la presence de description
        switch($this->description){
            case true:
                $description = '<div class="info-line collapse col-md-12" id="collapse-'. str_replace($this->unauthorizedChar,'',$this->name) .'">
                                    <p class="info-decription">'. $this->description .'</p>
                                </div>';

                $btcollapse = '<div class="bt-more-container">
                                    <button class="share-button bt" data-toggle="collapse" href="#collapse-'. str_replace($this->unauthorizedChar,'',$this->name) .'">
                                        '. $this->langFile[$this->pageName]->bt_myprofile_gamer_moredetails .'
                                    </button>
                                </div>';
                break;
            default:
                $description = '<div class="info-line collapse">
                                    <p class="info-decription"></p>
                                </div>';
                $btcollapse = '';
        }

        $content = '<div class="profile-elem profile-career-container col-md-12 '. $class .'" data-elem="'.$this->id.'">
                        <div class="profile-aside-container">
                            <div class="loader-container loader-elem-bloc loader-profile-elem">
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
                            <div class="profile-bloc-elem-left col-md-9">
                                <div class="infos-container col-md-12">
                                    <div class="info-line">
                                        <p class="info">'. $this->role .' '. $this->langGenerals->word_at .' </p><p class="info">'. $this->name .'</p>
                                    </div>
                                    <div class="info-line">
                                        <p class="info">'. $this->game .'</p><p class="info"> - '. $this->platform .'</p>
                                    </div>
                                    <div class="info-line">
                                        <p class="info">'. $this->monthStart .' '. $this->yearStart .'</p><p class="info"> - '. $currentPlayerTime .'</p>
                                    </div>
                                    '. $currentPlayHereContent .'
                                    '. $description .'                                      
                                </div>
                                '. $btcollapse .'
                            </div>
                            <div class="profile-bloc-elem-right col-md-3">
                                <div class="pic">
                                    '. $logo .'
                                </div>
                            </div>
                        </div>
                    </div>';
        
        return $content;
    }

    public function showUserTeam()
    {
        //test du logo: default ou non
        switch($this->logo){
            case 'default':
                $logo = '<img src="public/img/default/team.png" alt="WorldEsport Team logo">';
                break;
            default:
                if($this->userToDisplay)
                {
                    $logo = '<img src="inc/img/imgteams.php?imgname='. $this->logo .'&u='. $this->userToDisplay->pk_iduser .'" alt="WorldEsport Team logo">';
                }
                else{
                    $logo = '<img src="inc/img/imgteams.php?imgname='. $this->logo .'&u='. $this->currentUser->pk_iduser .'" alt="WorldEsport Team logo">';
                }
                break;

        }

        //test du currentplayhere, false or true
        switch($this->curplayhere){
            case true:
                $currentPlayHereContent = ' <div class="info-line current-work">
                                                <p>'. $this->langFile[$this->pageName]->text_currently_play_here .'</p>
                                            </div>';
                $currentPlayerTime = $this->langFile[$this->pageName]->word_now;
                $class = "";
                break;
            default:
                $currentPlayHereContent = '';
                $currentPlayerTime = $this->monthEnd .' '.$this->yearEnd;
                $class = "o-hidden";
        }

        //test de la presence de description
        switch($this->description){
            case true:
                $description = '<div class="info-line collapse col-md-12" id="collapse-'. str_replace($this->unauthorizedChar,'',$this->name) .'">
                                    <p class="info-decription">'. $this->description .'</p>
                                </div>';

                $btcollapse = '<div class="bt-more-container">
                                    <button class="share-button bt" id="add-follower" data-toggle="collapse" href="#collapse-'. str_replace($this->unauthorizedChar,'',$this->name) .'">
                                        '. $this->langFile[$this->pageName]->bt_myprofile_gamer_moredetails .'
                                    </button>
                                </div>';
                break;
            default:
                $description = '<div class="info-line collapse">
                                    <p class="info-decription"></p>
                                </div>';
                $btcollapse = '';
        }

        $content = '<div class="profile-elem profile-career-container col-md-12 '. $class .'" data-elem="'.$this->id.'">
                        <div class="profile-aside-container">                                                    
                            <div class="profile-bloc-elem-left col-md-9">
                                <div class="infos-container col-md-12">
                                    <div class="info-line">
                                        <p class="info">'. $this->role .' '. $this->langGenerals->word_at .'</p><p class="info">'. $this->name .'</p>
                                    </div>
                                    <div class="info-line">
                                        <p class="info">'. $this->game .'</p><p class="info"> - '. $this->platform .'</p>
                                    </div>
                                    <div class="info-line">
                                        <p class="info">'. $this->monthStart .' '. $this->yearStart .'</p><p class="info"> - '. $currentPlayerTime .'</p>
                                    </div>
                                    '. $currentPlayHereContent .'
                                    '. $description .'                                      
                                </div>
                                '. $btcollapse .'
                            </div>
                            <div class="profile-bloc-elem-right col-md-3">
                                <div class="pic">
                                    '. $logo .'
                                </div>
                            </div>
                        </div>
                    </div>';

        return $content;
    }

    public function show()
    {
        if(Session::getInstance()->read('current-state')['state'] == 'owner')
        {
            return $this->showMyTeam();
        }
        if(Session::getInstance()->read('current-state')['state'] == 'viewer')
        {
            return $this->showUserTeam();
        }
    }
}