<?php

namespace app\Table\Profile\Events;

use app\Table\AppDisplay;
use app\Table\Profile\Events\Event\displayEvent;
use app\Table\Profile\Events\EmployeeEvent\displayEmployeeEvent;

use core\Session\Session;

class displayEvents extends AppDisplay
{
    protected $pageName;
    private $events;
    private $employeeevents;

    public function __construct($events = false, $employeeevents = false, $userToDisplay = false)
    {
        $this->pageName = 'Profile';
        parent::__construct($userToDisplay, $this->pageName);

        $this->events = $events;
        $this->employeeevents = $employeeevents;
    }

    public function showEditFt()
    {
        return '<div class="bloc-edit-container-permanent">                               
                    <div class="title-edit-container">
                        <h1>'. $this->langFile[$this->pageName]->title_add_event .'</h1>
                    </div>
                    
                    <div class="field-container col-md-6">
                        <div class="label-field">
                            '. $this->langFile[$this->pageName]->title_field_myprofile_employee_event_eventname .'                         
                        </div>
                        <div class="input min-large comment-input editable-content" id="add-event-name" contenteditable="true" placeholder="ex: Gamers Assembly 2014 .." spellcheck="false"></div>
                        <div class="bulle-error">
                            <span class="message message-top"><span>'. $this->langFile[$this->pageName]->error_myprofile_name .'</span></span>
                            <span class="pseudo"></span>
                        </div>
                    </div>
                            
                    <div class="field-container col-md-6">
                        <div class="label-field">
                            '. $this->langFile[$this->pageName]->title_field_myprofile_employee_logo .'
                        </div>
                        <div class="input files comment-input logo-input-container" data-elem="logo-input-container" id="logo-event-input-container" text="Select file">
                            <div class="loader-container little loader-profile-elem loader-profile-upload-logo" id="loader-logo-events">
                                <span class="loader loader-double">
                                </span>
                            </div>
                            <input name="pic" id="add-event-logoevent" data-elem="logo-input" accept="image/*" type="file">
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
                        <div class="input min-large comment-input editable-content" id="add-event-game" contenteditable="true" placeholder="ex: Call of Duty 4 ..." spellcheck="false"></div>
                        <div class="bulle-error">
                            <span class="message message-top"><span>'. $this->langFile[$this->pageName]->error_myprofile_gamer_career_game .'</span></span>
                            <span class="pseudo"></span>
                        </div>
                    </div> 
                    
                    <div class="field-container col-md-6">
                        <div class="label-field">
                            '. $this->langFile[$this->pageName]->title_field_myprofile_gamer_career_platform .'
                        </div>
                        <div class="input min-large comment-input editable-content" id="add-event-platform" contenteditable="true" placeholder="ex: PC, XBOX ONE ..." spellcheck="false"></div>
                        <div class="bulle-error">
                            <span class="message message-top"><span>'. $this->langFile[$this->pageName]->error_myprofile_gamer_games_enterplatform .'</span></span>
                            <span class="pseudo"></span>
                        </div>
                    </div>
                                               
                    <div class="field-container col-md-6">
                        <div class="label-field">
                            '. $this->langFile[$this->pageName]->title_field_myprofile_gamer_career_teamname .'
                        </div>
                        <div class="input min-large comment-input editable-content" id="add-event-team" contenteditable="true" placeholder="ex: H2k ..." spellcheck="false"></div>
                        <div class="bulle-error">
                            <span class="message message-top"><span>'. $this->langFile[$this->pageName]->error_myprofile_employee_companyname .'</span></span>
                            <span class="pseudo"></span>
                        </div>
                    </div> 
                                                      
                    <div class="field-container col-md-6">
                        <div class="label-field">
                            '. $this->langFile[$this->pageName]->title_field_myprofile_gamer_career_role .'
                        </div>
                        <div class="input min-large comment-input editable-content" id="add-event-role" contenteditable="true" placeholder="ex: AK47, AWP ..."></div>
                        <div class="bulle-error">
                            <span class="message message-top"><span>'. $this->langFile[$this->pageName]->error_myprofile_gamer_career_role .'</span></span>
                            <span class="pseudo"></span>
                        </div>
                    </div>                                  
    
                    <div class="field-container col-md-6">                              
                        <div class="label-field">
                            '. $this->langFile[$this->pageName]->title_field_myprofile_employee_startdate .'
                        </div>
                        <div class="select-date day input comment-input col-md-2">
                            <select name="add-event-start-day" id="add-event-start-day">
                                <option value="dd">dd</option>
                                <option value="01">01</option>
                                <option value="02">02</option>
                                <option value="03">03</option>
                                <option value="04">04</option>
                                <option value="05">05</option>
                                <option value="06">06</option>
                                <option value="07">07</option>
                                <option value="08">08</option>
                                <option value="09">09</option>
                                <option value="10">10</option>
                                <option value="11">11</option>
                                <option value="12">12</option>
                                <option value="13">13</option>
                                <option value="14">14</option>
                                <option value="15">15</option>
                                <option value="16">16</option>
                                <option value="17">17</option>
                                <option value="18">18</option>
                                <option value="19">19</option>
                                <option value="20">20</option>
                                <option value="21">21</option>
                                <option value="22">22</option>
                                <option value="23">23</option>
                                <option value="24">24</option>
                                <option value="25">25</option>
                                <option value="26">26</option>
                                <option value="27">27</option>
                                <option value="28">28</option>
                                <option value="29">29</option>
                                <option value="30">30</option>
                                <option value="31">31</option>
                            </select>
                        </div>                              
                        <div class="select-date month input comment-input col-md-2">
                            <select name="add-event-start-month" id="add-event-start-month">
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
                            <select name="add-event-start-year" id="add-event-start-year">
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
                        <div class="select-date day input comment-input col-md-2">
                            <select name="add-event-end-day" id="add-event-end-day">
                                <option value="dd">dd</option>
                                <option value="01">01</option>
                                <option value="02">02</option>
                                <option value="03">03</option>
                                <option value="04">04</option>
                                <option value="05">05</option>
                                <option value="06">06</option>
                                <option value="07">07</option>
                                <option value="08">08</option>
                                <option value="09">09</option>
                                <option value="10">10</option>
                                <option value="11">11</option>
                                <option value="12">12</option>
                                <option value="13">13</option>
                                <option value="14">14</option>
                                <option value="15">15</option>
                                <option value="16">16</option>
                                <option value="17">17</option>
                                <option value="18">18</option>
                                <option value="19">19</option>
                                <option value="20">20</option>
                                <option value="21">21</option>
                                <option value="22">22</option>
                                <option value="23">23</option>
                                <option value="24">24</option>
                                <option value="25">25</option>
                                <option value="26">26</option>
                                <option value="27">27</option>
                                <option value="28">28</option>
                                <option value="29">29</option>
                                <option value="30">30</option>
                                <option value="31">31</option>
                            </select>
                        </div>                              
                        <div class="select-date month input comment-input col-md-2">
                            <select name="add-event-end-month" id="add-event-end-month">
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
                         <div class="bulle-error">
                            <span class="message message-top"><span>'. $this->langFile[$this->pageName]->error_myprofile_entervaliddate .'</span></span>
                            <span class="pseudo"></span>
                        </div>
                        <div class="select-date year input comment-input col-md-2">
                            <select name="add-event-end-year" id="add-event-end-year">
                                <option value="yyyy">yyyy</option>
                                '. $this->formElems->selectYearValues() .'
                            </select>
                        </div>                      
                        <div class="bulle-error-special">
                            <span class="message message-top"><span>'. $this->langFile[$this->pageName]->error_myprofile_incorrectdates .'</span></span>
                            <span class="pseudo"></span>
                        </div>  
                    </div>                        
                                         
                    <div class="field-container col-md-7">
                        <div class="label-field">
                            '. $this->langFile[$this->pageName]->title_field_result .'
                        </div>
                        <div class="input min-large comment-input editable-content" id="add-event-rank" contenteditable="true" placeholder="ex: 1, 2, 3, 9 - 16, unranked ..." spellcheck="false"></div>
                        <div class="bulle-error">
                            <span class="message message-top"><span>'. $this->langFile[$this->pageName]->error_myprofile_result .'</span></span>
                            <span class="pseudo"></span>
                        </div>
                    </div>
    
                    <div class="field-container col-md-12">
                        <div class="label-field">
                            '. $this->langFile[$this->pageName]->title_field_description .'
                        </div>
                        <div class="input descript comment-input share-bloc-text editable-content desc-input" id="add-event-descript" placeholder="'. $this->langFile[$this->pageName]->placeholder_give_more_details .'" contenteditable="true" spellcheck="false"></div>
                    </div>
    
                    <div class="update-button-container">
                        <div class="loader-container loader-bt" data-elem="loader-bt">
                            <div class="loader-double-container">
                                <span class="loader loader-double">
                                </span>
                            </div>
                        </div> 
                        <button class="share-button bt share-button-big" id="update-newevent">'. $this->langFile[$this->pageName]->bt_myprofile_updatebutton .'</button>
                    </div>
                </div>';
    }

    public function showEdit()
    {
        return '<div class="bloc-edit-container">                               
                    <div class="title-edit-container">
                        <h1>'. $this->langFile[$this->pageName]->title_add_event .'</h1>
                    </div>
                    
                    <div class="field-container col-md-6">
                        <div class="label-field">
                            '. $this->langFile[$this->pageName]->title_field_myprofile_employee_event_eventname .'
                        </div>
                        <div class="input min-large comment-input editable-content" id="add-event-name" contenteditable="true" placeholder="ex: Gamers Assembly 2014 .." spellcheck="false"></div>
                        <div class="bulle-error">
                            <span class="message message-top"><span>'. $this->langFile[$this->pageName]->error_myprofile_name .'</span></span>
                            <span class="pseudo"></span>
                        </div>
                    </div>
                            
                    <div class="field-container col-md-6">
                        <div class="label-field">
                            '. $this->langFile[$this->pageName]->title_field_myprofile_employee_logo .'
                        </div>
                        <div class="input files comment-input logo-input-container" data-elem="logo-input-container"id="logo-event-input-container" text="Select file">
                            <div class="loader-container little loader-profile-elem loader-profile-upload-logo" id="loader-logo-events">
                                <span class="loader loader-double">
                                </span>
                            </div>
                            <input name="pic" id="add-event-logoevent" data-elem="logo-input" accept="image/*" type="file">
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
                        <div class="input min-large comment-input editable-content" id="add-event-game" contenteditable="true" placeholder="ex: Call of Duty 4 ..." spellcheck="false"></div>
                        <div class="bulle-error">
                            <span class="message message-top"><span>'. $this->langFile[$this->pageName]->error_myprofile_gamer_career_game .'</span></span>
                            <span class="pseudo"></span>
                        </div>
                    </div> 
                    
                    <div class="field-container col-md-6">
                        <div class="label-field">
                            '. $this->langFile[$this->pageName]->title_field_myprofile_gamer_career_platform .'
                        </div>
                        <div class="input min-large comment-input editable-content" id="add-event-platform" contenteditable="true" placeholder="ex: PC, XBOX ONE ..." spellcheck="false"></div>
                        <div class="bulle-error">
                            <span class="message message-top"><span>'. $this->langFile[$this->pageName]->error_myprofile_gamer_games_enterplatform .'</span></span>
                            <span class="pseudo"></span>
                        </div>
                    </div>
                                               
                    <div class="field-container col-md-6">
                        <div class="label-field">
                            '. $this->langFile[$this->pageName]->title_field_myprofile_gamer_career_teamname .'
                        </div>
                        <div class="input min-large comment-input editable-content" id="add-event-team" contenteditable="true" placeholder="ex: H2k ..." spellcheck="false"></div>
                        <div class="bulle-error">
                            <span class="message message-top"><span>'. $this->langFile[$this->pageName]->error_myprofile_employee_companyname .'</span></span>
                            <span class="pseudo"></span>
                        </div>
                    </div> 
                                                      
                    <div class="field-container col-md-6">
                        <div class="label-field">
                            '. $this->langFile[$this->pageName]->title_field_myprofile_gamer_career_role .'
                        </div>
                        <div class="input min-large comment-input editable-content" id="add-event-role" contenteditable="true" placeholder="ex: AK47, AWP ..."></div>
                        <div class="bulle-error">
                            <span class="message message-top"><span>'. $this->langFile[$this->pageName]->error_myprofile_gamer_career_role .'</span></span>
                            <span class="pseudo"></span>
                        </div>
                    </div>                                  
    
                    <div class="field-container col-md-6">                              
                        <div class="label-field">
                            '. $this->langFile[$this->pageName]->title_field_myprofile_employee_startdate .'
                        </div>
                        <div class="select-date day input comment-input col-md-2">
                            <select name="add-event-start-day" id="add-event-start-day">
                                <option value="dd">dd</option>
                                <option value="01">01</option>
                                <option value="02">02</option>
                                <option value="03">03</option>
                                <option value="04">04</option>
                                <option value="05">05</option>
                                <option value="06">06</option>
                                <option value="07">07</option>
                                <option value="08">08</option>
                                <option value="09">09</option>
                                <option value="10">10</option>
                                <option value="11">11</option>
                                <option value="12">12</option>
                                <option value="13">13</option>
                                <option value="14">14</option>
                                <option value="15">15</option>
                                <option value="16">16</option>
                                <option value="17">17</option>
                                <option value="18">18</option>
                                <option value="19">19</option>
                                <option value="20">20</option>
                                <option value="21">21</option>
                                <option value="22">22</option>
                                <option value="23">23</option>
                                <option value="24">24</option>
                                <option value="25">25</option>
                                <option value="26">26</option>
                                <option value="27">27</option>
                                <option value="28">28</option>
                                <option value="29">29</option>
                                <option value="30">30</option>
                                <option value="31">31</option>
                            </select>
                        </div>                              
                        <div class="select-date month input comment-input col-md-2">
                            <select name="add-event-start-month" id="add-event-start-month">
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
                            <select name="add-event-start-year" id="add-event-start-year">
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
                        <div class="select-date day input comment-input col-md-2">
                            <select name="add-event-end-day" id="add-event-end-day">
                                <option value="dd">dd</option>
                                <option value="01">01</option>
                                <option value="02">02</option>
                                <option value="03">03</option>
                                <option value="04">04</option>
                                <option value="05">05</option>
                                <option value="06">06</option>
                                <option value="07">07</option>
                                <option value="08">08</option>
                                <option value="09">09</option>
                                <option value="10">10</option>
                                <option value="11">11</option>
                                <option value="12">12</option>
                                <option value="13">13</option>
                                <option value="14">14</option>
                                <option value="15">15</option>
                                <option value="16">16</option>
                                <option value="17">17</option>
                                <option value="18">18</option>
                                <option value="19">19</option>
                                <option value="20">20</option>
                                <option value="21">21</option>
                                <option value="22">22</option>
                                <option value="23">23</option>
                                <option value="24">24</option>
                                <option value="25">25</option>
                                <option value="26">26</option>
                                <option value="27">27</option>
                                <option value="28">28</option>
                                <option value="29">29</option>
                                <option value="30">30</option>
                                <option value="31">31</option>
                            </select>
                        </div>                              
                        <div class="select-date month input comment-input col-md-2">
                            <select name="add-event-end-month" id="add-event-end-month">
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
                         <div class="bulle-error">
                            <span class="message message-top"><span>'. $this->langFile[$this->pageName]->error_myprofile_entervaliddate .'</span></span>
                            <span class="pseudo"></span>
                        </div>
                        <div class="select-date year input comment-input col-md-2">
                            <select name="add-event-end-year" id="add-event-end-year">
                                <option value="yyyy">yyyy</option>
                               '. $this->formElems->selectYearValues() .'
                            </select>
                        </div>                      
                        <div class="bulle-error-special">
                            <span class="message message-top"><span>'. $this->langFile[$this->pageName]->error_myprofile_incorrectdates .'</span></span>
                            <span class="pseudo"></span>
                        </div>  
                    </div>                        
                                         
                    <div class="field-container col-md-7">
                        <div class="label-field">
                            '. $this->langFile[$this->pageName]->title_field_result .'
                        </div>
                        <div class="input min-large comment-input editable-content" id="add-event-rank" contenteditable="true" placeholder="ex: 1, 2, 3, 9 - 16, unranked ..." spellcheck="false"></div>
                        <div class="bulle-error">
                            <span class="message message-top"><span>'. $this->langFile[$this->pageName]->error_myprofile_result .'</span></span>
                            <span class="pseudo"></span>
                        </div>
                    </div>
    
                    <div class="field-container col-md-12">
                        <div class="label-field">
                            '. $this->langFile[$this->pageName]->title_field_description .'
                        </div>
                        <div class="input descript comment-input share-bloc-text editable-content desc-input" id="add-event-descript" placeholder="'. $this->langFile[$this->pageName]->placeholder_give_more_details .'" contenteditable="true" spellcheck="false"></div>
                    </div>
    
                    <div class="update-button-container">
                        <div class="loader-container loader-bt" data-elem="loader-bt">
                            <div class="loader-double-container">
                                <span class="loader loader-double">
                                </span>
                            </div>
                        </div> 
                        <button class="share-button bt share-button-big" id="update-newevent">'. $this->langFile[$this->pageName]->bt_myprofile_updatebutton .'</button>
                    </div>
                </div>';
    }

    public function showEmployeeEdit()
    {
        return '<div class="bloc-edit-container">                               
                    <div class="title-edit-container">
                        <h1>'. $this->langFile[$this->pageName]->title_add_event .'</h1>
                    </div>
                    
                    <div class="field-container col-md-8">
                        <div class="label-field">
                            '. $this->langFile[$this->pageName]->title_field_myprofile_employee_event_eventname .'
                        </div>
                        <div class="input min-large comment-input editable-content" id="add-employee-event-name" contenteditable="true" placeholder="ex: Gamers Assembly 2014 .." spellcheck="false"></div>
                        <div class="bulle-error">
                            <span class="message message-top"><span>'. $this->langFile[$this->pageName]->error_myprofile_name .'</span></span>
                            <span class="pseudo"></span>
                        </div>
                    </div>
                            
                    <div class="field-container col-md-8">
                        <div class="label-field">
                            '. $this->langFile[$this->pageName]->title_field_myprofile_employee_logo .'
                        </div>
                        <div class="input files comment-input logo-input-container" data-elem="logo-input-container" id="logo-empevents-input-container" text="Select file">
                            <div class="loader-container little loader-profile-elem loader-profile-upload-logo" id="loader-logo-empevents">
                                <span class="loader loader-double">
                                </span>
                            </div>
                            <input name="pic" id="add-employee-eventlogo" data-elem="logo-input" accept="image/*" type="file">
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
                            '. $this->langFile[$this->pageName]->title_field_myprofile_employee_startdate .'
                        </div>
                        <div class="select-date day input comment-input col-md-2">
                            <select name="add-employee-event-start-day" id="add-employee-event-start-day">
                                <option value="dd">dd</option>
                                <option value="01">01</option>
                                <option value="02">02</option>
                                <option value="03">03</option>
                                <option value="04">04</option>
                                <option value="05">05</option>
                                <option value="06">06</option>
                                <option value="07">07</option>
                                <option value="08">08</option>
                                <option value="09">09</option>
                                <option value="10">10</option>
                                <option value="11">11</option>
                                <option value="12">12</option>
                                <option value="13">13</option>
                                <option value="14">14</option>
                                <option value="15">15</option>
                                <option value="16">16</option>
                                <option value="17">17</option>
                                <option value="18">18</option>
                                <option value="19">19</option>
                                <option value="20">20</option>
                                <option value="21">21</option>
                                <option value="22">22</option>
                                <option value="23">23</option>
                                <option value="24">24</option>
                                <option value="25">25</option>
                                <option value="26">26</option>
                                <option value="27">27</option>
                                <option value="28">28</option>
                                <option value="29">29</option>
                                <option value="30">30</option>
                                <option value="31">31</option>
                            </select>
                        </div>                              
                        <div class="select-date month input comment-input col-md-2">
                            <select name="add-employee-event-start-month" id="add-employee-event-start-month">
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
                        <div class="bulle-error">
                            <span class="message message-top"><span>'. $this->langFile[$this->pageName]->error_myprofile_entervaliddate .'</span></span>
                            <span class="pseudo"></span>
                        </div>
                        <div class="select-date year input comment-input col-md-2">
                            <select name="add-employee-event-start-year" id="add-employee-event-start-year">
                                <option value="yyyy">yyyy</option>
                                '. $this->formElems->selectYearValues() .'
                            </select>
                        </div>                                                     
                    </div> 
            
                    <div class="field-container col-md-6 gsdfdf">                              
                        <div class="label-field">
                            '. $this->langFile[$this->pageName]->title_field_myprofile_employee_enddate .'
                        </div>
                        <div class="select-date day input comment-input col-md-2">
                            <select name="add-employee-event-end-day" id="add-employee-event-end-day">
                                <option value="dd">dd</option>
                                <option value="01">01</option>
                                <option value="02">02</option>
                                <option value="03">03</option>
                                <option value="04">04</option>
                                <option value="05">05</option>
                                <option value="06">06</option>
                                <option value="07">07</option>
                                <option value="08">08</option>
                                <option value="09">09</option>
                                <option value="10">10</option>
                                <option value="11">11</option>
                                <option value="12">12</option>
                                <option value="13">13</option>
                                <option value="14">14</option>
                                <option value="15">15</option>
                                <option value="16">16</option>
                                <option value="17">17</option>
                                <option value="18">18</option>
                                <option value="19">19</option>
                                <option value="20">20</option>
                                <option value="21">21</option>
                                <option value="22">22</option>
                                <option value="23">23</option>
                                <option value="24">24</option>
                                <option value="25">25</option>
                                <option value="26">26</option>
                                <option value="27">27</option>
                                <option value="28">28</option>
                                <option value="29">29</option>
                                <option value="30">30</option>
                                <option value="31">31</option>
                            </select>
                        </div>                              
                        <div class="select-date month input comment-input col-md-2">
                            <select name="add-employee-event-end-month" id="add-employee-event-end-month">
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
                        <div class="bulle-error">
                            <span class="message message-top"><span>'. $this->langFile[$this->pageName]->error_myprofile_entervaliddate .'</span></span>
                            <span class="pseudo"></span>
                        </div>
                        <div class="select-date year input comment-input col-md-2">
                            <select name="add-employee-event-end-year" id="add-employee-event-end-year">
                                <option value="yyyy">yyyy</option>
                               '. $this->formElems->selectYearValues() .'
                            </select>
                        </div>                      
                        <div class="bulle-error-special">
                            <span class="message message-top"><span>'. $this->langFile[$this->pageName]->error_myprofile_incorrectdates .'</span></span>
                            <span class="pseudo"></span>
                        </div>  
                    </div>  
                    
                    <div class="field-container col-md-8">
                        <div class="label-field">
                            '. $this->langFile[$this->pageName]->title_field_myprofile_employee_event_jobtitle .'
                        </div>
                        <div class="input min-large comment-input editable-content" id="add-employee-event-jobtitle" contenteditable="true" placeholder="ex: Journalist ..." spellcheck="false"></div>
                        <div class="bulle-error">
                            <span class="message message-top"><span>'. $this->langFile[$this->pageName]->error_myprofile_employee_enterjobtitle .'</span></span>
                            <span class="pseudo"></span>
                        </div>
                    </div>         
                                               
                    <div class="field-container col-md-8">
                        <div class="label-field">
                            '. $this->langFile[$this->pageName]->title_field_myprofile_employee_event_company .'
                        </div>
                        <div class="input min-large comment-input editable-content" id="add-employee-event-company" contenteditable="true" placeholder="ex: JeuxVideo.com ..." spellcheck="false"></div>
                        <div class="bulle-error">
                            <span class="message message-top"><span>'. $this->langFile[$this->pageName]->error_myprofile_employee_companyname .'</span></span>
                            <span class="pseudo"></span>
                        </div>
                    </div>                 
                   
                    <div class="field-container col-md-12">
                        <div class="label-field">
                            '. $this->langFile[$this->pageName]->title_field_description .'
                        </div>
                        <div class="input descript comment-input share-bloc-text editable-content desc-input" id="add-employee-event-descript" placeholder="'. $this->langFile[$this->pageName]->placeholder_give_more_details .'" contenteditable="true" spellcheck="false"></div>
                    </div>
    
                    <div class="update-button-container">
                        <div class="loader-container loader-bt" data-elem="loader-bt">
                            <div class="loader-double-container">
                                <span class="loader loader-double">
                                </span>
                            </div>
                        </div> 
                        <button class="share-button bt share-button-big" id="update-newemployeeevent">'. $this->langFile[$this->pageName]->bt_myprofile_updatebutton .'</button>
                    </div>
                </div>';
    }

    public function showEmployeeEditFt()
    {
        return '<div class="bloc-edit-container-permanent">                               
                    <div class="title-edit-container">
                        <h1>'. $this->langFile[$this->pageName]->title_add_event .'</h1>
                    </div>
                    
                    <div class="field-container col-md-8">
                        <div class="label-field">
                            '. $this->langFile[$this->pageName]->title_field_myprofile_employee_event_eventname .'
                        </div>
                        <div class="input min-large comment-input editable-content" id="add-employee-event-name" contenteditable="true" placeholder="ex: Gamers Assembly 2014 .." spellcheck="false"></div>
                        <div class="bulle-error">
                            <span class="message message-top"><span>'. $this->langFile[$this->pageName]->error_myprofile_name .'</span></span>
                            <span class="pseudo"></span>
                        </div>
                    </div>
                            
                    <div class="field-container col-md-8">
                        <div class="label-field">
                            '. $this->langFile[$this->pageName]->title_field_myprofile_employee_logo .'
                        </div>
                        <div class="input files comment-input logo-input-container" data-elem="logo-input-container"id="logo-empevents-input-container" text="Select file">
                            <div class="loader-container little loader-profile-elem loader-profile-upload-logo" id="loader-logo-empevents">
                                <span class="loader loader-double">
                                </span>
                            </div>
                            <input name="pic" id="add-employee-eventlogo" data-elem="logo-input" accept="image/*" type="file">
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
                            '. $this->langFile[$this->pageName]->title_field_myprofile_employee_startdate .'
                        </div>
                        <div class="select-date day input comment-input col-md-2">
                            <select name="add-employee-event-start-day" id="add-employee-event-start-day">
                                <option value="dd">dd</option>
                                <option value="01">01</option>
                                <option value="02">02</option>
                                <option value="03">03</option>
                                <option value="04">04</option>
                                <option value="05">05</option>
                                <option value="06">06</option>
                                <option value="07">07</option>
                                <option value="08">08</option>
                                <option value="09">09</option>
                                <option value="10">10</option>
                                <option value="11">11</option>
                                <option value="12">12</option>
                                <option value="13">13</option>
                                <option value="14">14</option>
                                <option value="15">15</option>
                                <option value="16">16</option>
                                <option value="17">17</option>
                                <option value="18">18</option>
                                <option value="19">19</option>
                                <option value="20">20</option>
                                <option value="21">21</option>
                                <option value="22">22</option>
                                <option value="23">23</option>
                                <option value="24">24</option>
                                <option value="25">25</option>
                                <option value="26">26</option>
                                <option value="27">27</option>
                                <option value="28">28</option>
                                <option value="29">29</option>
                                <option value="30">30</option>
                                <option value="31">31</option>
                            </select>
                        </div>                              
                        <div class="select-date month input comment-input col-md-2">
                            <select name="add-employee-event-start-month" id="add-employee-event-start-month">
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
                        <div class="bulle-error">
                            <span class="message message-top"><span>'. $this->langFile[$this->pageName]->error_myprofile_entervaliddate .'</span></span>
                            <span class="pseudo"></span>
                        </div>
                        <div class="select-date year input comment-input col-md-2">
                            <select name="add-employee-event-start-year" id="add-employee-event-start-year">
                                <option value="yyyy">yyyy</option>
                                '. $this->formElems->selectYearValues() .'
                            </select>
                        </div>                                                     
                    </div> 
            
                    <div class="field-container col-md-6 gsdfdf">                              
                        <div class="label-field">
                            '. $this->langFile[$this->pageName]->title_field_myprofile_employee_enddate .'
                        </div>
                        <div class="select-date day input comment-input col-md-2">
                            <select name="add-employee-event-end-day" id="add-employee-event-end-day">
                                <option value="dd">dd</option>
                                <option value="01">01</option>
                                <option value="02">02</option>
                                <option value="03">03</option>
                                <option value="04">04</option>
                                <option value="05">05</option>
                                <option value="06">06</option>
                                <option value="07">07</option>
                                <option value="08">08</option>
                                <option value="09">09</option>
                                <option value="10">10</option>
                                <option value="11">11</option>
                                <option value="12">12</option>
                                <option value="13">13</option>
                                <option value="14">14</option>
                                <option value="15">15</option>
                                <option value="16">16</option>
                                <option value="17">17</option>
                                <option value="18">18</option>
                                <option value="19">19</option>
                                <option value="20">20</option>
                                <option value="21">21</option>
                                <option value="22">22</option>
                                <option value="23">23</option>
                                <option value="24">24</option>
                                <option value="25">25</option>
                                <option value="26">26</option>
                                <option value="27">27</option>
                                <option value="28">28</option>
                                <option value="29">29</option>
                                <option value="30">30</option>
                                <option value="31">31</option>
                            </select>
                        </div>                              
                        <div class="select-date month input comment-input col-md-2">
                            <select name="add-employee-event-end-month" id="add-employee-event-end-month">
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
                        <div class="bulle-error">
                            <span class="message message-top"><span>'. $this->langFile[$this->pageName]->error_myprofile_entervaliddate .'</span></span>
                            <span class="pseudo"></span>
                        </div>
                        <div class="select-date year input comment-input col-md-2">
                            <select name="add-employee-event-end-year" id="add-employee-event-end-year">
                                <option value="yyyy">yyyy</option>
                                '. $this->formElems->selectYearValues() .'
                            </select>
                        </div>                      
                        <div class="bulle-error-special">
                            <span class="message message-top"><span>'. $this->langFile[$this->pageName]->error_myprofile_incorrectdates .'</span></span>
                            <span class="pseudo"></span>
                        </div>  
                    </div>  
                    
                    <div class="field-container col-md-8">
                        <div class="label-field">
                            '. $this->langFile[$this->pageName]->title_field_myprofile_employee_jobtitle .'
                        </div>
                        <div class="input min-large comment-input editable-content" id="add-employee-event-jobtitle" contenteditable="true" placeholder="ex: Journalist ..." spellcheck="false"></div>
                        <div class="bulle-error">
                            <span class="message message-top"><span>'. $this->langFile[$this->pageName]->error_myprofile_employee_enterjobtitle .'</span></span>
                            <span class="pseudo"></span>
                        </div>
                    </div>         
                                               
                    <div class="field-container col-md-8">
                        <div class="label-field">
                            '. $this->langFile[$this->pageName]->title_field_myprofile_employee_event_company .'
                        </div>
                        <div class="input min-large comment-input editable-content" id="add-employee-event-company" contenteditable="true" placeholder="ex: JeuxVideo.com ..." spellcheck="false"></div>
                        <div class="bulle-error">
                            <span class="message message-top"><span>'. $this->langFile[$this->pageName]->error_myprofile_employee_companyname .'</span></span>
                            <span class="pseudo"></span>
                        </div>
                    </div>                 
                   
                    <div class="field-container col-md-12">
                        <div class="label-field">
                            '. $this->langFile[$this->pageName]->title_field_description .'
                        </div>
                        <div class="input descript comment-input share-bloc-text editable-content desc-input" id="add-employee-event-descript" placeholder="'. $this->langFile[$this->pageName]->placeholder_give_more_details .'" contenteditable="true" spellcheck="false"></div>
                    </div>
    
                    <div class="update-button-container">
                        <div class="loader-container loader-bt" data-elem="loader-bt">
                            <div class="loader-double-container">
                                <span class="loader loader-double">
                                </span>
                            </div>
                        </div> 
                        <button class="share-button bt share-button-big" id="update-newemployeeevent">'. $this->langFile[$this->pageName]->bt_myprofile_updatebutton .'</button>
                    </div>
                </div>';
    }

    public function showEmptyEvents()
    {
        return '<div class="bloc-container empty">
                    <div class="message-empty-container">
                        <p><span class="bold">'. $this->userToDisplay->nickname .'</span> '. $this->langFile[$this->pageName]->title_myprofile_emptycontent_event .'</p>                       
                    </div>                    
                </div>';
    }

    public function showMyEventBody()
    {
        $content = '';
        foreach($this->events as $event)
        {
            $display = new displayEvent($event, $this->userToDisplay);
            $content = $content . $display->showMyEvent();
        }
        return $content;
    }

    public function showUserEventBody()
    {
        $content = '';
        foreach($this->events as $event)
        {
            $display = new displayEvent($event, $this->userToDisplay);
            $content = $content . $display->showUserEvent();
        }
        return $content;
    }

    public function showEmployeeBody()
    {
        $content = '';
        foreach($this->employeeevents as $event)
        {
            $display = new displayEmployeeEvent($event);
            $content = $content . $display->showEvent();
        }
        return $content;
    }

    public function showUserEmployeeBody()
    {
        $content = '';
        foreach($this->employeeevents as $event)
        {
            $display = new displayEmployeeEvent($event, $this->userToDisplay);
            $content = $content . $display->show();
        }
        return $content;
    }

    public function showEmptyEmployeeEvents()
    {
        return '<div class="bloc-container empty">
                    <div class="message-empty-container">
                        <p><span class="bold">'. $this->userToDisplay->nickname .'</span> '. $this->langFile[$this->pageName]->title_myprofile_emptycontent_employeeevent .'</p>
                    </div>                    
                </div>';
    }

    public function showGamerEvents()
    {
        if(Session::getInstance()->read('current-state')['state'] == 'owner')
        {
            if (empty($this->events))
            {
                return $this->showMyEventsft($this->showEditFt());
            }
            else {
                return $this->showMyEvents($this->showMyEventBody());
            }
        }
        if(Session::getInstance()->read('current-state')['state'] == 'viewer')
        {
            if (empty($this->events))
            {
                return $this->showUserEventsEmpty($this->showEmptyEvents());
            }
            else {
                return $this->showUserEvents($this->showUserEventBody());
            }
        }
    }

    public function showEmployeeEvents()
    {
        if(Session::getInstance()->read('current-state')['state'] == 'owner')
        {
            if (empty($this->employeeevents))
            {
                return $this->showMyEmployeeEventsft($this->showEmployeeEditFt());
            }
            else {
                return $this->showMyEmployeeEvents($this->showEmployeeBody());
            }
        }
        if(Session::getInstance()->read('current-state')['state'] == 'viewer')
        {
            if (empty($this->employeeevents))
            {
                return $this->showUserEmployeeEventsEmpty($this->showEmptyEmployeeEvents());
            }
            else {
                return $this->showUserEmployeeEvents($this->showUserEmployeeBody());
            }
        }
    }
}