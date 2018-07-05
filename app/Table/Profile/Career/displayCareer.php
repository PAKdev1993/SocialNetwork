<?php

namespace app\Table\Profile\Career;

use app\Table\AppDisplay;
use app\Table\Profile\Career\Company\displayCompany;
use app\Table\Profile\Career\Team\displayTeam;

use app\Table\UserModel\UserModel;
use core\Session\Session;

class displayCareer extends AppDisplay
{
    private $teams;
    private $companies;

    //VARIABLES LINKED TO LANGS
    protected $pageName;

    //HERITED VARIABLES
    protected $userToDisplay;

    public function __construct($teams = false, $companies = false, $userToDisplay = false)
    {
        $this->pageName = 'Profile';
        parent::__construct($userToDisplay, $this->pageName);
        $this->teams =      $teams;
        $this->companies =  $companies;
    }

    public function showEditFt()
    {
        return '<div class="bloc-edit-container-permanent">
                    <div class="title-edit-container">
                        <h1>'. $this->langFile[$this->pageName]->title_add_team .'</h1>
                    </div>
                         
                    <div class="field-container col-md-8">
                        <div class="label-field">
                            '. $this->langFile[$this->pageName]->title_field_myprofile_gamer_career_teamname .'
                        </div>
                        <div class="input min-large comment-input editable-content" placeholder="ex: H2k, NIP" contenteditable="true" id="add-team-teamname" spellcheck="false"></div>
                        <div class="bulle-error">
                            <span class="message message-top"><span>'. $this->langFile[$this->pageName]->error_myprofile_name .'</span></span>
                            <span class="pseudo"></span>
                        </div> 
                    </div>
                         
                    <div class="field-container col-md-8">
                        <div class="label-field">
                            '. $this->langFile[$this->pageName]->title_field_myprofile_employee_logo .'
                        </div>
                        <div class="input files comment-input logo-input-container" data-elem="logo-input-container" text="Select file">
                            <div class="loader-container little loader-profile-elem loader-profile-upload-logo" id="loader-logo-teams">
                                <span class="loader loader-double">
                                </span>
                            </div>
                            <input name="pic" id="add-team-logoteam" data-elem="logo-input" accept="image/*" type="file">
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
                        <div class="input min-large comment-input editable-content" placeholder="ex: Call of Duty 4 ..." contenteditable="true" id="add-team-game" spellcheck="false"></div>
                        <div class="bulle-error">
                            <span class="message message-top"><span>'. $this->langFile[$this->pageName]->error_myprofile_gamer_career_game .'</span></span>
                            <span class="pseudo"></span>
                        </div>
                    </div>

                    <div class="field-container col-md-6">
                        <div class="label-field">
                            '. $this->langFile[$this->pageName]->title_field_myprofile_gamer_career_platform .'
                        </div>
                        <div class="input min-large comment-input editable-content" placeholder="ex: PC, XBOX ONE ..."contenteditable="true" id="add-team-plateform" spellcheck="false"></div>
                        <div class="bulle-error">
                            <span class="message message-top"><span>'. $this->langFile[$this->pageName]->error_myprofile_gamer_career_platform .'</span></span>
                            <span class="pseudo"></span>
                        </div>
                    </div>

                    <div class="field-container col-md-8">
                        <div class="label-field">
                            '. $this->langFile[$this->pageName]->title_field_myprofile_gamer_career_role .'
                        </div>
                        <div class="input min-large comment-input editable-content" placeholder="ex: AWP, AK ..." contenteditable="true" id="add-team-role" spellcheck="false"></div>
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
                            <select name="addteam-start-month" id="addteam-start-month">
                                <option value="mm">mm</option>
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
                            <select name="addteam-start-year" id="addteam-start-year">
                                <option value="yyyy">yyyy</option>
                                '. $this->formElems->selectYearValues() .'
                            </select>
                        </div>
                        <div class="bulle-error">
                            <span class="message message-top"><span>'. $this->langFile[$this->pageName]->error_myprofile_entervaliddate .'</span></span>
                            <span class="pseudo"></span>
                        </div>
                    </div>

                    <div class="field-container hided-field col-md-6">
                        <div class="label-field">
                            '. $this->langFile[$this->pageName]->title_field_myprofile_employee_enddate .'
                        </div>
                        <div class="select-date month input comment-input col-md-2">
                            <select name="addteam-end-month" id="addteam-end-month">
                                <option value="mm">mm</option>
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
                            <select name="addteam-end-year" id="addteam-end-year">
                                <option value="yyyy">yyyy</option>
                                '. $this->formElems->selectYearValues() .'
                            </select>
                        </div>
                        <div class="bulle-error">
                            <span class="message message-top"><span>'. $this->langFile[$this->pageName]->error_myprofile_entervaliddate .'</span></span>
                            <span class="pseudo"></span>
                        </div>
                    </div>

                    <div class="field-container col-md-12">
                        <input name="currently-played" value="" class="input" id="addteam-current-activity" type="checkbox" checked>
                        <label for="addteam-current-activity">'. $this->langFile[$this->pageName]->title_field_myprofile_gamer_currentlyplay .'</label>
                    </div>

                    <div class="field-container col-md-12">
                        <div class="label-field">
                            '. $this->langFile[$this->pageName]->title_field_description .'
                        </div>
                        <div class="input descript comment-input share-bloc-text editable-content desc-input" placeholder="'. $this->langFile[$this->pageName]->placeholder_give_more_details .'" spellcheck="false" contenteditable="true" id="add-team-decript" spellcheck="false"></div>
                    </div>

                    <div class="update-button-container">
                         <div class="loader-container loader-bt" data-elem="loader-bt">
                            <div class="loader-double-container">
                                <span class="loader loader-double">
                                </span>
                            </div>
                        </div> 
                        <button class="share-button bt share-button-big" id="update-newteam">'. $this->langFile[$this->pageName]->bt_myprofile_updatebutton .'</button>
                    </div>
                </div>';
    }

    public function showEdit()
    {
        return '<div class="bloc-edit-container">
                    <div class="title-edit-container">
                        <h1>'. $this->langFile[$this->pageName]->title_add_team .'</h1>
                    </div>
                         
                    <div class="field-container col-md-8">
                        <div class="label-field">
                           '. $this->langFile[$this->pageName]->title_field_myprofile_gamer_career_teamname .'
                        </div>
                        <div class="input min-large comment-input editable-content" placeholder="ex: H2k, NIP" contenteditable="true" id="add-team-teamname" spellcheck="false"></div>
                        <div class="bulle-error">
                            <span class="message message-top"><span>'. $this->langFile[$this->pageName]->error_myprofile_name .'</span></span>
                            <span class="pseudo"></span>
                        </div> 
                    </div>
                         
                    <div class="field-container col-md-8">
                        <div class="label-field">
                            '. $this->langFile[$this->pageName]->title_field_myprofile_employee_logo .'
                        </div>
                        <div class="input files comment-input" id="logo-team-input-container" data-elem="logo-input-container" text="Select file">
                            <!-- LOADER -->
                            <div class="loader-container little loader-profile-elem loader-profile-upload-logo" id="loader-logo-teams">
                                <span class="loader loader-double">
                                </span>
                            </div>
                            <!-- /LOADER -->
                            <!-- INPUT -->
                            <input name="pic" id="add-team-logoteam" data-elem="logo-input" accept="image/*" type="file">
                            <!-- /INPUT -->
                            <!-- ERRORS UP -->
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
                            <!-- /ERRORS UP -->
                        </div>                       
                    </div>

                    <div class="field-container col-md-6">
                        <div class="label-field">
                            '. $this->langFile[$this->pageName]->title_field_myprofile_gamer_career_game .'
                        </div>
                        <div class="input min-large comment-input editable-content" placeholder="ex: Call of Duty 4 ..." contenteditable="true" id="add-team-game" spellcheck="false"></div>
                        <div class="bulle-error">
                            <span class="message message-top"><span>'. $this->langFile[$this->pageName]->error_myprofile_gamer_career_game .'</span></span>
                            <span class="pseudo"></span>
                        </div>
                    </div>

                    <div class="field-container col-md-6">
                        <div class="label-field">
                             '. $this->langFile[$this->pageName]->title_field_myprofile_gamer_career_platform .'
                        </div>
                        <div class="input min-large comment-input editable-content" placeholder="ex: PC, XBOX ONE ..."contenteditable="true" id="add-team-plateform" spellcheck="false"></div>
                        <div class="bulle-error">
                            <span class="message message-top"><span>'. $this->langFile[$this->pageName]->error_myprofile_gamer_career_platform .'</span></span>
                            <span class="pseudo"></span>
                        </div>
                    </div>

                    <div class="field-container col-md-8">
                        <div class="label-field">
                           '. $this->langFile[$this->pageName]->title_field_myprofile_gamer_career_role .'
                        </div>
                        <div class="input min-large comment-input editable-content" placeholder="ex: AWP, AK ..." contenteditable="true" id="add-team-role" spellcheck="false"></div>
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
                            <select name="addteam-start-month" id="addteam-start-month">
                                <option value="mm">mm</option>
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
                            <select name="addteam-start-year" id="addteam-start-year">
                                <option value="yyyy">yyyy</option>
                                '. $this->formElems->selectYearValues() .'
                            </select>
                        </div>
                        <div class="bulle-error">
                            <span class="message message-top"><span>'. $this->langFile[$this->pageName]->error_myprofile_entervaliddate .'</span></span>
                            <span class="pseudo"></span>
                        </div>
                    </div>

                    <div class="field-container col-md-6">
                        <div class="label-field">
                            '. $this->langFile[$this->pageName]->title_field_myprofile_employee_enddate .'
                        </div>
                        <div class="select-date month input comment-input col-md-2">
                            <select name="addteam-end-month" id="addteam-end-month">
                                <option value="mm">mm</option>
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
                            <select name="addteam-end-year" id="addteam-end-year">
                                <option value="yyyy">yyyy</option>
                                '. $this->formElems->selectYearValues() .'
                            </select>
                        </div>
                        <div class="bulle-error">
                            <span class="message message-top"><span>'. $this->langFile[$this->pageName]->error_myprofile_entervaliddate .'</span></span>
                            <span class="pseudo"></span>
                        </div>
                    </div>

                    <div class="field-container col-md-12">
                        <input name="currently-played" value="" class="input" id="addteam-current-activity" type="checkbox">
                        <label for="addteam-current-activity">'. $this->langFile[$this->pageName]->title_field_myprofile_gamer_currentlyplay .'</label>
                    </div>

                    <div class="field-container col-md-12">
                        <div class="label-field">
                            '. $this->langFile[$this->pageName]->title_field_description .'
                        </div>
                        <div class="input descript comment-input share-bloc-text editable-content desc-input" placeholder="'. $this->langFile[$this->pageName]->placeholder_give_more_details .'" spellcheck="false" contenteditable="true" id="add-team-decript" spellcheck="false"></div>
                    </div>

                    <div class="update-button-container">
                         <div class="loader-container loader-bt" data-elem="loader-bt">
                            <div class="loader-double-container">
                                <span class="loader loader-double">
                                </span>
                            </div>
                        </div>  
                        <button class="share-button bt share-button-big" id="update-newteam">'. $this->langFile[$this->pageName]->bt_myprofile_updatebutton .'</button>
                    </div>
                </div>';
    }

    public function showMyTeamsBody()
    {
        $content = '';
        foreach($this->teams as $team)
        {
            $display = new displayTeam($team);
            $content = $content . $display->show();
        }
        return $content;
    }

    public function showUserTeamsBody()
    {
        $content = '';
        foreach($this->teams as $team)
        {
            $display = new displayTeam($team, $this->userToDisplay); //#todo OPTMISER: ne pas travailler qu'avec une seule instance d'AppDisplay !! AppDisplay reExtend ds
            $content = $content . $display->show();
        }
        return $content;
    }

    public function showEmptyTeams()
    {
        return '<div class="bloc-container empty">
                    <div class="message-empty-container">
                        <p><span class="bold">'. $this->userToDisplay->nickname .'</span> '. $this->langFile[$this->pageName]->title_myprofile_emptycontent_team .'</p>
                    </div>                    
                </div>';
    }

    //#todo fusionner les partie edit et editft
    public function showEmployeeEditft()
    {
        return '<div class="bloc-edit-container">
                    <div class="title-edit-container">
                        <h1>'. $this->langFile[$this->pageName]->title_field_myprofile_employee_career_addcompany .'</h1>
                    </div>
                         
                    <div class="field-container col-md-8">
                        <div class="label-field">
                            '. $this->langFile[$this->pageName]->title_field_myprofile_employee_companyname .'
                        </div>
                        <div class="input min-large comment-input editable-content" placeholder="ex: Google, Apache ..." contenteditable="true" id="add-company-companyname" spellcheck="false"></div>
                        <div class="bulle-error">
                            <span class="message message-top"><span>'. $this->langFile[$this->pageName]->error_myprofile_name .'</span></span>
                            <span class="pseudo"></span>
                        </div> 
                    </div>
                         
                    <div class="field-container col-md-8">
                        <div class="label-field">
                            '. $this->langFile[$this->pageName]->title_field_myprofile_employee_logo .'
                        </div>
                        <div class="input files comment-input logo-input-container" id="logo-company-input-container" data-elem="logo-input-container" text="Select file">
                            <div class="loader-container little loader-profile-elem loader-profile-upload-logo" id="loader-logo-empcompany">
                                <span class="loader loader-double">
                                </span>
                            </div>
                            <input name="pic" id="add-company-companylogo" data-elem="logo-input" accept="image/*" type="file">
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
                            '. $this->langFile[$this->pageName]->title_field_myprofile_employee_city .'
                        </div>
                        <div class="input min-large comment-input editable-content" placeholder="ex: Paris ..." contenteditable="true" id="add-company-companycity" spellcheck="false"></div>
                        <div class="bulle-error">
                            <span class="message message-top"><span>'. $this->langFile[$this->pageName]->error_myprofile_employee_entercity .'</span></span>
                            <span class="pseudo"></span>
                        </div>
                    </div>

                    <div class="field-container col-md-6">
                        <div class="label-field">
                            '. $this->langFile[$this->pageName]->title_field_myprofile_employee_country .'
                        </div>
                        <div class="input min-large comment-input editable-content" placeholder="ex: France ..."contenteditable="true" id="add-company-companycountry" spellcheck="false"></div>
                        <div class="bulle-error">
                            <span class="message message-top"><span>'. $this->langFile[$this->pageName]->error_myprofile_employee_entercountry .'</span></span>
                            <span class="pseudo"></span>
                        </div>
                    </div>

                    <div class="field-container col-md-8">
                        <div class="label-field">
                            '. $this->langFile[$this->pageName]->title_field_myprofile_employee_jobtitle .'
                        </div>
                        <div class="input min-large comment-input editable-content" placeholder="ex: AWP, AK ..." contenteditable="true" id="add-company-companyjobtitle" spellcheck="false"></div>
                        <div class="bulle-error">
                            <span class="message message-top"><span>'. $this->langFile[$this->pageName]->error_myprofile_employee_enterjobtitle .'</span></span>
                            <span class="pseudo"></span>
                        </div>
                    </div>

                    <div class="field-container col-md-6">
                        <div class="label-field">
                            '. $this->langFile[$this->pageName]->title_field_myprofile_employee_startdate .'
                        </div>                        
                        <div class="select-date month input comment-input col-md-2">
                            <select name="add-company-start-month" id="add-company-start-month">
                                <option value="mm">mm</option>
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
                            <select name="add-company-start-year" id="add-company-start-year">
                                <option value="yyyy">yyyy</option>
                                '. $this->formElems->selectYearValues() .'
                            </select>
                        </div>
                        <div class="bulle-error">
                            <span class="message message-top"><span>'. $this->langFile[$this->pageName]->error_myprofile_entervaliddate .'</span></span>
                            <span class="pseudo"></span>
                        </div>
                    </div>

                    <div class="field-container hided-field col-md-6">
                        <div class="label-field">
                            '. $this->langFile[$this->pageName]->title_field_myprofile_employee_enddate .'
                        </div>                         
                        <div class="select-date month input comment-input col-md-2">
                            <select name="add-company-end-month" id="add-company-end-month">
                                <option value="mm">mm</option>
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
                            <select name="add-company-end-year" id="add-company-end-year">
                                <option value="yyyy">yyyy</option>
                                '. $this->formElems->selectYearValues() .''. $this->formElems->selectYearValues() .'
                            </select>
                        </div>
                        <div class="bulle-error">
                            <span class="message message-top"><span>'. $this->langFile[$this->pageName]->error_myprofile_entervaliddate .'</span></span>
                            <span class="pseudo"></span>
                        </div>
                    </div>

                    <div class="field-container col-md-12">
                        <input name="currently-work" value="" class="input" id="add-company-current-activity" type="checkbox" checked>
                        <label for="add-company-current-activity">'. $this->langFile[$this->pageName]->title_field_myprofile_employee_currentlywork .'</label>
                    </div>

                    <div class="field-container col-md-12">
                        <div class="label-field">
                             '. $this->langFile[$this->pageName]->title_field_description .'
                        </div>
                        <div class="input descript comment-input share-bloc-text editable-content desc-input" placeholder="'. $this->langFile[$this->pageName]->placeholder_give_more_details .'" spellcheck="false" contenteditable="true" id="add-company-decript" spellcheck="false"></div>
                    </div>

                    <div class="update-button-container">
                         <div class="loader-container loader-bt" data-elem="loader-bt">
                            <div class="loader-double-container">
                                <span class="loader loader-double">
                                </span>
                            </div>
                        </div> 
                        <button class="share-button bt share-button-big" id="update-newcompany">'. $this->langFile[$this->pageName]->bt_myprofile_updatebutton .'</button>
                    </div>
                </div>';
    }

    public function showEmployeeEdit()
    {
        return '<div class="bloc-edit-container">
                    <div class="title-edit-container">
                        <h1>'. $this->langFile[$this->pageName]->title_field_myprofile_employee_career_addcompany .'</h1>
                    </div>
                         
                    <div class="field-container col-md-8">
                        <div class="label-field">
                            '. $this->langFile[$this->pageName]->title_field_myprofile_employee_companyname .'
                        </div>
                        <div class="input min-large comment-input editable-content" placeholder="ex: Google, Apache ..." contenteditable="true" id="add-company-companyname" spellcheck="false"></div>
                        <div class="bulle-error">
                            <span class="message message-top"><span>'. $this->langFile[$this->pageName]->error_myprofile_name .'</span></span>
                            <span class="pseudo"></span>
                        </div> 
                    </div>
                         
                    <div class="field-container col-md-8">
                        <div class="label-field">
                           '. $this->langFile[$this->pageName]->title_field_myprofile_employee_logo .'
                        </div>
                        <div class="input files comment-input logo-input-container" data-elem="logo-input-container" id="logo-company-input-container" text="Select file">
                            <div class="loader-container little loader-profile-elem loader-profile-upload-logo" id="loader-logo-empcompany">
                                <span class="loader loader-double">
                                </span>
                            </div>
                            <input name="pic" id="add-company-companylogo" data-elem="logo-input" accept="image/*" type="file">
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
                            '. $this->langFile[$this->pageName]->title_field_myprofile_employee_city .'
                        </div>
                        <div class="input min-large comment-input editable-content" placeholder="ex: Paris ..." contenteditable="true" id="add-company-companycity" spellcheck="false"></div>
                        <div class="bulle-error">
                            <span class="message message-top"><span>'. $this->langFile[$this->pageName]->error_myprofile_employee_entercity .'</span></span>
                            <span class="pseudo"></span>
                        </div>
                    </div>

                    <div class="field-container col-md-6">
                        <div class="label-field">
                            '. $this->langFile[$this->pageName]->title_field_myprofile_employee_country .'
                        </div>
                        <div class="input min-large comment-input editable-content" placeholder="ex: France ..."contenteditable="true" id="add-company-companycountry" spellcheck="false"></div>
                        <div class="bulle-error">
                            <span class="message message-top"><span>'. $this->langFile[$this->pageName]->error_myprofile_employee_entercountry .'</span></span>
                            <span class="pseudo"></span>
                        </div>
                    </div>

                    <div class="field-container col-md-8">
                        <div class="label-field">
                             '. $this->langFile[$this->pageName]->title_field_myprofile_employee_jobtitle .'
                        </div>
                        <div class="input min-large comment-input editable-content" placeholder="ex: Developer, Team manager ..." contenteditable="true" id="add-company-companyjobtitle" spellcheck="false"></div>
                        <div class="bulle-error">
                            <span class="message message-top"><span>'. $this->langFile[$this->pageName]->error_myprofile_employee_enterjobtitle .'</span></span>
                            <span class="pseudo"></span>
                        </div>
                    </div>

                    <div class="field-container col-md-6">
                        <div class="label-field">
                            '. $this->langFile[$this->pageName]->title_field_myprofile_employee_startdate .'
                        </div>                        
                        <div class="select-date month input comment-input col-md-2">
                            <select name="add-company-start-month" id="add-company-start-month">
                                <option value="mm">mm</option>
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
                            <select name="add-company-start-year" id="add-company-start-year">
                                <option value="yyyy">yyyy</option>
                                <option value="2016">2016</option>
                                <option value="2015">2015</option>
                                <option value="2014">2014</option>
                                <option value="2013">2013</option>
                                <option value="2012">2012</option>
                                <option value="2011">2011</option>
                                <option value="2010">2010</option>
                                <option value="2009">2009</option>
                                <option value="2008">2008</option>
                                <option value="2007">2007</option>
                                <option value="2006">2006</option>
                                <option value="2005">2005</option>
                                <option value="2004">2004</option>
                                <option value="2003">2003</option>
                                <option value="2002">2002</option>
                                <option value="2001">2001</option>
                                <option value="2000">2000</option>
                                <option value="1999">1999</option>
                                <option value="1998">1998</option>
                                <option value="1997">1997</option>
                                <option value="1996">1996</option>
                                <option value="1995">1995</option>
                                <option value="1994">1994</option>
                                <option value="1993">1993</option>
                                <option value="1992">1992</option>
                                <option value="1991">1991</option>
                                <option value="1990">1990</option>
                                <option value="1989">1989</option>
                                <option value="1988">1988</option>
                                <option value="1987">1987</option>
                                <option value="1986">1986</option>
                                <option value="1985">1985</option>
                                <option value="1984">1984</option>
                                <option value="1983">1983</option>
                                <option value="1982">1982</option>
                                <option value="1981">1981</option>
                                <option value="1980">1980</option>
                                <option value="1979">1979</option>
                                <option value="1978">1978</option>
                                <option value="1977">1977</option>
                                <option value="1976">1976</option>
                                <option value="1975">1975</option>
                                <option value="1974">1974</option>
                                <option value="1973">1973</option>
                                <option value="1972">1972</option>
                                <option value="1971">1971</option>
                                <option value="1970">1970</option>
                                <option value="1969">1969</option>
                                <option value="1968">1968</option>
                                <option value="1967">1967</option>
                                <option value="1966">1966</option>
                                <option value="1965">1965</option>
                                <option value="1964">1964</option>
                                <option value="1963">1963</option>
                                <option value="1962">1962</option>
                                <option value="1961">1961</option>
                                <option value="1960">1960</option>
                                <option value="1959">1959</option>
                                <option value="1958">1958</option>
                                <option value="1957">1957</option>
                                <option value="1956">1956</option>
                                <option value="1955">1955</option>
                                <option value="1954">1954</option>
                                <option value="1953">1953</option>
                                <option value="1952">1952</option>
                                <option value="1951">1951</option>
                                <option value="1950">1950</option>
                                <option value="1949">1949</option>
                                <option value="1948">1948</option>
                                <option value="1947">1947</option>
                                <option value="1946">1946</option>
                                <option value="1945">1945</option>
                                <option value="1944">1944</option>
                                <option value="1943">1943</option>
                                <option value="1942">1942</option>
                                <option value="1941">1941</option>
                                <option value="1940">1940</option>
                                <option value="1939">1939</option>
                                <option value="1938">1938</option>
                                <option value="1937">1937</option>
                                <option value="1936">1936</option>
                                <option value="1935">1935</option>
                                <option value="1934">1934</option>
                                <option value="1933">1933</option>
                                <option value="1932">1932</option>
                                <option value="1931">1931</option>
                                <option value="1930">1930</option>
                                <option value="1929">1929</option>
                                <option value="1928">1928</option>
                                <option value="1927">1927</option>
                                <option value="1926">1926</option>
                                <option value="1925">1925</option>
                                <option value="1924">1924</option>
                                <option value="1923">1923</option>
                                <option value="1922">1922</option>
                                <option value="1921">1921</option>
                                <option value="1920">1920</option>
                                <option value="1919">1919</option>
                                <option value="1918">1918</option>
                                <option value="1917">1917</option>
                                <option value="1916">1916</option>
                                <option value="1915">1915</option>
                                <option value="1914">1914</option>
                                <option value="1913">1913</option>
                                <option value="1912">1912</option>
                                <option value="1911">1911</option>
                                <option value="1910">1910</option>
                                <option value="1909">1909</option>
                                <option value="1908">1908</option>
                                <option value="1907">1907</option>
                                <option value="1906">1906</option>
                                <option value="1905">1905</option>
                            </select>
                        </div>
                        <div class="bulle-error">
                            <span class="message message-top"><span>'. $this->langFile[$this->pageName]->error_myprofile_entervaliddate .'</span></span>
                            <span class="pseudo"></span>
                        </div>
                    </div>

                    <div class="field-container col-md-6">
                        <div class="label-field">
                            '. $this->langFile[$this->pageName]->title_field_myprofile_employee_enddate .'
                        </div>                         
                        <div class="select-date month input comment-input col-md-2">
                            <select name="add-company-end-month" id="add-company-end-month">
                                <option value="mm">mm</option>
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
                            <select name="add-company-end-year" id="add-company-end-year">
                                <option value="yyyy">yyyy</option>
                                <option value="2016">2016</option>
                                <option value="2015">2015</option>
                                <option value="2014">2014</option>
                                <option value="2013">2013</option>
                                <option value="2012">2012</option>
                                <option value="2011">2011</option>
                                <option value="2010">2010</option>
                                <option value="2009">2009</option>
                                <option value="2008">2008</option>
                                <option value="2007">2007</option>
                                <option value="2006">2006</option>
                                <option value="2005">2005</option>
                                <option value="2004">2004</option>
                                <option value="2003">2003</option>
                                <option value="2002">2002</option>
                                <option value="2001">2001</option>
                                <option value="2000">2000</option>
                                <option value="1999">1999</option>
                                <option value="1998">1998</option>
                                <option value="1997">1997</option>
                                <option value="1996">1996</option>
                                <option value="1995">1995</option>
                                <option value="1994">1994</option>
                                <option value="1993">1993</option>
                                <option value="1992">1992</option>
                                <option value="1991">1991</option>
                                <option value="1990">1990</option>
                                <option value="1989">1989</option>
                                <option value="1988">1988</option>
                                <option value="1987">1987</option>
                                <option value="1986">1986</option>
                                <option value="1985">1985</option>
                                <option value="1984">1984</option>
                                <option value="1983">1983</option>
                                <option value="1982">1982</option>
                                <option value="1981">1981</option>
                                <option value="1980">1980</option>
                                <option value="1979">1979</option>
                                <option value="1978">1978</option>
                                <option value="1977">1977</option>
                                <option value="1976">1976</option>
                                <option value="1975">1975</option>
                                <option value="1974">1974</option>
                                <option value="1973">1973</option>
                                <option value="1972">1972</option>
                                <option value="1971">1971</option>
                                <option value="1970">1970</option>
                                <option value="1969">1969</option>
                                <option value="1968">1968</option>
                                <option value="1967">1967</option>
                                <option value="1966">1966</option>
                                <option value="1965">1965</option>
                                <option value="1964">1964</option>
                                <option value="1963">1963</option>
                                <option value="1962">1962</option>
                                <option value="1961">1961</option>
                                <option value="1960">1960</option>
                                <option value="1959">1959</option>
                                <option value="1958">1958</option>
                                <option value="1957">1957</option>
                                <option value="1956">1956</option>
                                <option value="1955">1955</option>
                                <option value="1954">1954</option>
                                <option value="1953">1953</option>
                                <option value="1952">1952</option>
                                <option value="1951">1951</option>
                                <option value="1950">1950</option>
                                <option value="1949">1949</option>
                                <option value="1948">1948</option>
                                <option value="1947">1947</option>
                                <option value="1946">1946</option>
                                <option value="1945">1945</option>
                                <option value="1944">1944</option>
                                <option value="1943">1943</option>
                                <option value="1942">1942</option>
                                <option value="1941">1941</option>
                                <option value="1940">1940</option>
                                <option value="1939">1939</option>
                                <option value="1938">1938</option>
                                <option value="1937">1937</option>
                                <option value="1936">1936</option>
                                <option value="1935">1935</option>
                                <option value="1934">1934</option>
                                <option value="1933">1933</option>
                                <option value="1932">1932</option>
                                <option value="1931">1931</option>
                                <option value="1930">1930</option>
                                <option value="1929">1929</option>
                                <option value="1928">1928</option>
                                <option value="1927">1927</option>
                                <option value="1926">1926</option>
                                <option value="1925">1925</option>
                                <option value="1924">1924</option>
                                <option value="1923">1923</option>
                                <option value="1922">1922</option>
                                <option value="1921">1921</option>
                                <option value="1920">1920</option>
                                <option value="1919">1919</option>
                                <option value="1918">1918</option>
                                <option value="1917">1917</option>
                                <option value="1916">1916</option>
                                <option value="1915">1915</option>
                                <option value="1914">1914</option>
                                <option value="1913">1913</option>
                                <option value="1912">1912</option>
                                <option value="1911">1911</option>
                                <option value="1910">1910</option>
                                <option value="1909">1909</option>
                                <option value="1908">1908</option>
                                <option value="1907">1907</option>
                                <option value="1906">1906</option>
                                <option value="1905">1905</option>
                            </select>
                        </div>
                        <div class="bulle-error">
                            <span class="message message-top"><span>'. $this->langFile[$this->pageName]->error_myprofile_entervaliddate .'</span></span>
                            <span class="pseudo"></span>
                        </div>
                    </div>

                    <div class="field-container col-md-12">
                        <input name="currently-work" value="" class="input" id="add-company-current-activity" type="checkbox">
                        <label for="add-company-current-activity">'. $this->langFile[$this->pageName]->title_field_myprofile_employee_currentlywork .'</label>
                    </div>

                    <div class="field-container col-md-12">
                        <div class="label-field">
                             '. $this->langFile[$this->pageName]->title_field_description .'
                        </div>
                        <div class="input descript comment-input share-bloc-text editable-content desc-input" placeholder="'. $this->langFile[$this->pageName]->placeholder_give_more_details .'" spellcheck="false" contenteditable="true" id="add-company-decript" spellcheck="false"></div>
                    </div>

                    <div class="update-button-container"> 
                        <div class="loader-container loader-bt" data-elem="loader-bt">
                            <div class="loader-double-container">
                                <span class="loader loader-double">
                                </span>
                            </div>
                        </div>                   
                        <button class="share-button bt share-button-big" id="update-newcompany">
                            '. $this->langFile[$this->pageName]->bt_myprofile_updatebutton .'                           
                        </button>
                    </div>
                </div>';
    }

    public function showMyEmployeeBody()
    {
        $content = '';
        foreach($this->companies as $company)
        {
            $display = new displayCompany($company);
            $content = $content . $display->show();
        }
        return $content;
    }

    public function showUserEmployeeBody()
    {
        $content = '';
        foreach($this->companies as $company)
        {
            $display = new displayCompany($company, $this->userToDisplay);
            $content = $content . $display->show();
        }
        return $content;
    }

    public function showEmptyEmployeeCareer()
    {
        return '<div class="bloc-container empty">
                    <div class="message-empty-container">
                        <p><span class="bold">'. $this->userToDisplay->nickname .'</span> '. $this->langFile[$this->pageName]->title_myprofile_emptycontent_company .'</p>                      
                    </div>                    
                </div>';
    }

    //display gamer career ds my profile
    public function showGamerCareer()
    {
        if(Session::getInstance()->read('current-state')['state'] == 'owner')
        {
            if(empty($this->teams))
            {
                return $this->showMyCareerft($this->showEditFt());
            }
            else{
                return $this->showMyCareer($this->showMyTeamsBody());
            }
        }
        if(Session::getInstance()->read('current-state')['state'] == 'viewer')
        {
            if(empty($this->teams))
            {
                return $this->showUserCareerEmpty($this->showEmptyTeams());
            }
            else{
                return $this->showUserCareer($this->showUserTeamsBody());
            }
        }
    }

    //display employee career ds my profile
    public function showEmployeeCareer()
    {
        if(Session::getInstance()->read('current-state')['state'] == 'owner')
        {
            if(empty($this->companies))
            {
                return $this->showMyEmployeeCareerft($this->showEmployeeEditft());
            }
            else{
                return $this->showMyEmployeeCareer($this->showMyEmployeeBody());
            }
        }
        if(Session::getInstance()->read('current-state')['state'] == 'viewer')
        {
            if(empty($this->companies))
            {
                return $this->showUserEmployeeCareerEmpty($this->showEmptyEmployeeCareer());
            }
            else{
                return $this->showUserEmployeeCareer($this->showUserEmployeeBody());
            }
        }
    }
}