<?php

namespace app\Table\Profile\Events\EmployeeEvent;

use app\Table\AppDisplayElem;
use app\Table\Profile\Events\displayEvents;
use core\Session\Session;

class displayEmployeeEvent extends displayEvents
{
    //EMPLOYEE EVENTS ATTRIBUTES
    private $id;
    private $name;
    private $logo;
    private $jobtitle;
    private $startdate;
    private $enddate;
    private $company;
    private $description;

    //DATE VARIABLES
    private $dayStart = 'mm';
    private $monthStart = 'mm';
    private $yearStart = 'yyyy';

    private $dayEnd = 'mm';
    private $monthEnd = 'mm';
    private $yearEnd = 'yyyy';

    private $monthStartValue;
    private $monthEndValue;

    //USER VARIABLES
    protected $userToDisplay;

    public function __construct($event, $userToDisplay = false)
    {
        parent::__construct();
        $this->id =             (empty($event->pk_ideventcareer))   ? '' : $event->pk_ideventcareer;
        $this->name =           (empty($event->name))               ? '' : $event->name;
        $this->logo =           (empty($event->logo))               ? '' : $event->logo;
        $this->jobtitle =       (empty($event->jobtitle))           ? '' : $event->jobtitle;
        $this->startdate =      (empty($event->startdate))          ? '' : $event->startdate;
        $this->enddate =        (empty($event->enddate))            ? '' : $event->enddate;
        $this->company =        (empty($event->company))            ? '' : $event->company;
        $this->description =    (empty($event->description))        ? '' : $event->description;

        if($this->id)
        {
            //USER
            $this->userToDisplay = $userToDisplay ? $userToDisplay : Session::getInstance()->read('auth');

            //transform startDate to insert in form
            $dateFormated = date("d-m-Y", strtotime($this->startdate));
            $pieces = explode('-', $dateFormated);
            $this->dayStart = $pieces[0];
            $this->monthStartValue = $pieces[1];
            $this->yearStart = $pieces[2];

            $corresMonth = (int) ltrim($pieces[1], '0');
            $monthName = $this->montharray[(int)$corresMonth];
            //Old $this->monthStart = substr($this->monthTraduce->$monthName,0,3);
            $this->monthStart = $this->monthTraduce->$monthName;

            //transform endDate to insert in form
            $dateFormated = date("d-m-Y", strtotime($this->enddate));
            $pieces = explode('-', $dateFormated);
            $this->dayEnd = $pieces[0];
            $this->monthEndValue = $pieces[1];
            $this->yearEnd = $pieces[2];

            $corresMonth = (int) ltrim($pieces[1], '0');
            $monthName = $this->montharray[(int)$corresMonth];
            //Old $this->monthEnd = substr($this->monthTraduce->$monthName,0,3);
            $this->monthEnd = $this->monthTraduce->$monthName;
        }
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
                    <div class="field-container col-md-6">
                        <div class="label-field">
                            '. $this->langFile[$this->pageName]->title_field_myprofile_employee_event_eventname .'
                        </div>
                        <div class="input min-large comment-input editable-content" id="edit-empevent-name" contenteditable="true" placeholder="ex: Gamers Assembly 2014 ..">'. $this->name .'</div>
                        <div class="bulle-error">
                            <span class="message message-top"><span>'. $this->langFile[$this->pageName]->error_myprofile_name .'</span></span>
                            <span class="pseudo"></span>
                        </div>
                    </div>
                            
                    <div class="field-container col-md-6">
                        <div class="label-field">
                            '. $this->langFile[$this->pageName]->title_field_myprofile_employee_logo .'
                        </div>
                        <div class="input files comment-input logo-input-container" data-elem="logo-input-container" id="logo-empevents-input-container" text="Select file">
                            <div class="loader-container little loader-profile-elem loader-profile-upload-logo" id="loader-logo-empevents">
                                <span class="loader loader-double">
                                </span>
                            </div>
                            <input name="pic" id="edit-empevent-logo" data-elem="logo-input" accept="image/*" type="file">
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
                                              
                    <div class="field-container col-md-7">
                        <div class="label-field">
                            '. $this->langFile[$this->pageName]->title_field_myprofile_employee_jobtitle .'
                        </div>
                        <div class="input min-large comment-input editable-content" id="edit-empevent-jobtitle" contenteditable="true" placeholder="ex: AK47, AWP ...">'. $this->jobtitle .'</div>
                        <div class="bulle-error">
                            <span class="message message-top"><span>'. $this->langFile[$this->pageName]->error_myprofile_employee_enterjobtitle .'</span></span>
                            <span class="pseudo"></span>
                        </div>
                    </div>
                                                      
                    <div class="field-container col-md-7">
                        <div class="label-field">
                           '. $this->langFile[$this->pageName]->title_field_myprofile_employee_event_company .'
                        </div>
                        <div class="input min-large comment-input editable-content" id="edit-empevent-company" contenteditable="true" placeholder="ex: AK47, AWP ...">'. $this->company .'</div>
                        <div class="bulle-error">
                            <span class="message message-top"><span>'. $this->langFile[$this->pageName]->error_myprofile_name .'</span></span>
                            <span class="pseudo"></span>
                        </div>
                    </div> 
    
                    <div class="field-container col-md-6">                              
                        <div class="label-field">
                            '. $this->langFile[$this->pageName]->title_field_myprofile_employee_startdate .'
                        </div>
                        <div class="select-date day input comment-input col-md-2">
                            <select name="edit-event-start-day" id="edit-empevent-start-day">
                                <option value="'. $this->dayStart .'">'. $this->dayStart .'</option>
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
                            <select name="edit-event-start-month" id="edit-empevent-start-month">
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
                            <select name="edit-event-start-year" id="edit-empevent-start-year">
                                <option value="'. $this->yearStart .'">'. $this->yearStart .'</option>
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
                            <select name="edit-event-end-day" id="edit-empevent-end-day">
                                <option value="'. $this->dayEnd .'">'. $this->dayEnd .'</option>
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
                            <select name="edit-event-end-month" id="edit-empevent-end-month">
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
                        <div class="select-date year input comment-input col-md-2">
                            <select name="edit-event-end-year" id="edit-empevent-end-year">
                                <option value="'. $this->yearEnd .'">'. $this->yearEnd .'</option>
                                '. $this->formElems->selectYearValues() .'
                                <option value="1905">1905</option>
                            </select>
                        </div>  
                        <div class="bulle-error">
                            <span class="message message-top"><span>'. $this->langFile[$this->pageName]->error_myprofile_entervaliddate .'</span></span>
                            <span class="pseudo"></span>
                        </div>                             
                    </div>           
    
                    <div class="field-container col-md-12">
                        <div class="label-field">
                            '. $this->langFile[$this->pageName]->title_field_description .'
                        </div>
                        <div class="input descript comment-input share-bloc-text editable-content desc-input" id="edit-empevent-descript" placeholder="'. $this->langFile[$this->pageName]->placeholder_give_more_details .'" contenteditable="true">'. $this->description .'</div>
                    </div>
    
                    <div class="update-button-container">
                        <div class="loader-container loader-bt" data-elem="loader-bt">
                            <div class="loader-double-container">
                                <span class="loader loader-double">
                                </span>
                            </div>
                        </div> 
                        <button class="share-button bt share-button-big" id="update-empevent">'. $this->langFile[$this->pageName]->bt_myprofile_updatebutton .'</button>
                    </div>
                </div>';
    }

    public function showEvent()
    {
        //test de la presence de logo ou non
        switch($this->logo){
            case 'default':
                $logo = '<img src="public/img/default/event.png" alt="WorldEsport logo">';
                break;
            default:
                $logo = '<img src="inc/img/imgempevents.php?imgname='. $this->logo .'&u='. $this->currentUser->pk_iduser .'" alt="WorldEsport employee events logo">';
        }

        //test de la presence de description ou non
        switch($this->description){
            case true:
                $description = '<div class="info-line collapse" id="collapse'. substr(str_replace($this->unauthorizedChar,'',$this->name),0,15) . str_replace($this->unauthorizedChar,'',$this->startdate) .'">
                                    <p class="info-decription">' . $this->description . '</p>
                                </div>';
                $btcollapse = '<div class="bt-more-container">
                                    <button class="share-button bt" data-toggle="collapse" href="#collapse'. substr(str_replace($this->unauthorizedChar,'',$this->name),0,15) . str_replace($this->unauthorizedChar,'',$this->startdate) .'">
                                        '. $this->langFile[$this->pageName]->bt_myprofile_gamer_moredetails .'
                                    </button>
                                </div>';
                break;
            default:
                $description = '<div class="info-line collapse">
                                    <p class="info-decription">' . $this->description . '</p>
                                </div>';
                $btcollapse = '';
        }

        $content = ' <div class="profile-elem profil-event-container col-md-12" data-elem="'.$this->id.'">
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
                                        <p class="info">'. $this->name .'</p>
                                    </div>
                                    <div class="info-line">
                                        <p class="info">'. $this->jobtitle .' '. $this->langGenerals->word_at .' </p><p class="info">'. $this->company .'</p>
                                    </div>                                  
                                    <div class="info-line">
                                        <p class="info">' . $this->dayStart . ' ' . $this->monthStart . ' ' . $this->yearStart . ' - </p><p class="info">' . $this->dayEnd . ' ' . $this->monthEnd . ' ' . $this->yearEnd . '</p>
                                    </div>
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

    public function showUserEvent()
    {
        //test de la presence de logo ou non
        switch($this->logo){
            case 'default':
                $logo = '<img src="public/img/default/event.png" alt="WorldEsport logo">';
                break;
            default:
                $logo = '<img src="inc/img/imgempevents.php?imgname='. $this->logo .'&u='. $this->userToDisplay->pk_iduser .'" alt="WorldEsport employee events logo">';
        }

        //test de la presence de description ou non
        switch($this->description){
            case true:
                $description = '<div class="info-line collapse" id="collapse'. substr(str_replace($this->unauthorizedChar,'',$this->name),0,15) . str_replace($this->unauthorizedChar,'',$this->startdate) .'">
                                    <p class="info-decription">' . $this->description . '</p>
                                </div>';
                $btcollapse = '<div class="bt-more-container">
                                    <button class="share-button bt" data-toggle="collapse" href="#collapse'. substr(str_replace($this->unauthorizedChar,'',$this->name),0,15) . str_replace($this->unauthorizedChar,'',$this->startdate) .'">
                                        '. $this->langFile[$this->pageName]->bt_myprofile_gamer_moredetails .'
                                    </button>
                                </div>';
                break;
            default:
                $description = '<div class="info-line collapse">
                                    <p class="info-decription">' . $this->description . '</p>
                                </div>';
                $btcollapse = '';
        }

        $content = ' <div class="profile-elem profil-event-container col-md-12" data-elem="'.$this->id.'">
                        <div class="profile-aside-container">                           
                            <div class="profile-bloc-elem-left col-md-9">
                                <div class="infos-container col-md-12">
                                    <div class="info-line">
                                        <p class="info">'. $this->name .'</p>
                                    </div>
                                    <div class="info-line">
                                        <p class="info">'. $this->jobtitle .' '. $this->langGenerals->word_at .' </p><p class="info">'. $this->company .'</p>
                                    </div>                                  
                                    <div class="info-line">
                                        <p class="info">' . $this->dayStart . ' ' . $this->monthStart . ' ' . $this->yearStart . ' - </p><p class="info">' . $this->dayEnd . ' ' . $this->monthEnd . ' ' . $this->yearEnd . '</p>
                                    </div>
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
            return $this->showEvent();
        }
        if(Session::getInstance()->read('current-state')['state'] == 'viewer')
        {
            return $this->showUserEvent();
        }
    }
}