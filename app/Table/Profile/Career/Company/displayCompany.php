<?php

namespace app\Table\Profile\Career\Company;

use app\Table\AppDisplayElem;
use app\Table\Profile\Career\displayCareer;
use core\Session\Session;

class displayCompany extends displayCareer
{
    //COMPANY ATTRIBUTES
    private $id;
    private $name;
    private $logo;
    private $city;
    private $country;
    private $jobtitle;
    private $startdate;
    private $enddate;
    private $description;
    private $currentlyWorkHere;

    //DATE VARIABLES
    private $monthStart = 'mm';
    private $yearStart = 'yyyy';

    private $monthEnd = 'mm';
    private $yearEnd = 'yyyy';

    private $monthStartValue;
    private $monthEndValue;

    //USER VARIABLES
    protected $userToDisplay;
    
    public function __construct($company, $userToDisplay = false)
    {
        parent::__construct();
        $this->id =             (empty($company->pk_idcompanycareer))   ? '' : $company->pk_idcompanycareer;
        $this->name =           (empty($company->name))                 ? '' : $company->name;
        $this->logo =           (empty($company->logo))                 ? '' : $company->logo;
        $this->city =           (empty($company->city))                 ? '' : $company->city;
        $this->country =        (empty($company->country))              ? '' : $company->country;
        $this->jobtitle =       (empty($company->jobtitle))             ? '' : $company->jobtitle;
        $this->startdate =      (empty($company->startdate))            ? '' : $company->startdate;
        $this->enddate =        (empty($company->enddate))              ? '' : $company->enddate;
        $this->description =    (empty($company->description))          ? '' : $company->description;
        $this->currentlyWorkHere = (empty($company->currentlyWorkHere)) ? '' : $company->currentlyWorkHere;
        
        if($this->id)
        {
            //USER
            $this->userToDisplay = $userToDisplay ? $userToDisplay : Session::getInstance()->read('auth'); //#todo changer ca, ici on redefini userToDisplay, pas coherent, car il existe deja une definition ds AppDisplay
            
            //transform startDate to insert in form
            $dateFormated = date("d-m-Y", strtotime($this->startdate));
            $pieces = explode('-', $dateFormated);
            $this->monthStartValue = $pieces[1];
            $this->yearStart = $pieces[2];

            $corresMonth = (int) ltrim($pieces[1], '0');
            $monthName = $this->montharray[(int)$corresMonth];
            $this->monthStart = $this->monthTraduce->$monthName;

            //transform endDate to insert in form
            $dateFormated = date("d-m-Y", strtotime($this->enddate));
            $pieces = explode('-', $dateFormated);
            $this->monthEndValue = $pieces[1];
            $this->yearEnd = $pieces[2];

            $corresMonth = (int) ltrim($pieces[1], '0');
            $monthName = $this->montharray[(int)$corresMonth];
            $this->monthEnd = $this->monthTraduce->$monthName;
        }
    }
    
    public function showEdit()
    {
        $statecurworkhere = "";
        if($this->currentlyWorkHere != 0)
        {
            $statecurworkhere = 'checked';

            $enddatefield = '<div class="field-container hided-field col-md-6" id="enddate-field">
                                <div class="label-field">
                                    '. $this->langFile[$this->pageName]->title_field_myprofile_employee_enddate .'
                                </div>
                                <div class="select-date month input comment-input col-md-2">
                                    <select name="edit-team-end-month" id="edit-company-end-month">
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
                                    <select name="edit-team-end-year" id="edit-company-end-year">
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
        else{
            $enddatefield = '<div class="field-container col-md-6 enddate" id="enddate-field">
                                    <div class="label-field">
                                        '. $this->langFile[$this->pageName]->title_field_myprofile_employee_enddate .'
                                    </div>
                                    <div class="select-date month input comment-input col-md-2">
                                        <select name="edit-team-end-month" id="edit-company-end-month">
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
                                        <select name="edit-team-end-year" id="edit-company-end-year">
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
                        <h1>'. $this->langFile[$this->pageName]->word_edit .' '.$this->name .'</h1>
                    </div>
                         
                    <div class="field-container col-md-8">
                        <div class="label-field">
                            '. $this->langFile[$this->pageName]->title_field_myprofile_employee_event_company .'
                        </div>
                        <div class="input min-large comment-input editable-content" placeholder="ex: Google, Apache ..." contenteditable="true" id="edit-company-name" spellcheck="false">'.$this->name .'</div>
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
                            <div class="loader-container little loader-profile-elem loader-profile-upload-logo" id="loader-logo-empcompany">
                                <span class="loader loader-double">
                                </span>
                            </div>
                            <input name="pic" id="edit-company-logo"  data-elem="logo-input" accept="image/*" type="file">
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
                        <div class="input min-large comment-input editable-content" placeholder="ex: Paris ..." contenteditable="true" id="edit-company-city" spellcheck="false">'.$this->city .'</div>
                        <div class="bulle-error">
                            <span class="message message-top"><span>'. $this->langFile[$this->pageName]->error_myprofile_employee_entercity .'</span></span>
                            <span class="pseudo"></span>
                        </div>
                    </div>

                    <div class="field-container col-md-6">
                        <div class="label-field">
                            '. $this->langFile[$this->pageName]->title_field_myprofile_employee_country .'
                        </div>
                        <div class="input min-large comment-input editable-content" placeholder="ex: France ..."contenteditable="true" id="edit-company-country" spellcheck="false">'.$this->country .'</div>
                        <div class="bulle-error">
                            <span class="message message-top"><span>'. $this->langFile[$this->pageName]->error_myprofile_employee_entercountry .'</span></span>
                            <span class="pseudo"></span>
                        </div>
                    </div>

                    <div class="field-container col-md-8">
                        <div class="label-field">
                           '. $this->langFile[$this->pageName]->title_field_myprofile_employee_jobtitle .'
                        </div>
                        <div class="input min-large comment-input editable-content" placeholder="ex: AWP, AK ..." contenteditable="true" id="edit-company-jobtitle" spellcheck="false">'.$this->jobtitle .'</div>
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
                            <select name="edit-company-start-month" id="edit-company-start-month">
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
                            <select name="edit-company-start-year" id="edit-company-start-year">
                                <option value="'. $this->yearStart .'">'. $this->yearStart .'</option>
                                '. $this->formElems->selectYearValues() .'
                            </select>
                        </div>
                        <div class="bulle-error">
                            <span class="message message-top"><span>'. $this->langFile[$this->pageName]->error_myprofile_incorrectdates .'</span></span>
                            <span class="pseudo"></span>
                        </div>
                    </div>

                    '. $enddatefield .'

                    <div class="field-container col-md-12">
                        <input name="currently-work" value="" class="input" id="edit-company-current-activity" type="checkbox" '. $statecurworkhere .'>
                        <label for="edit-company-current-activity">'. $this->langFile[$this->pageName]->text_currently_work_here .'</label>
                    </div>

                    <div class="field-container col-md-12">
                        <div class="label-field">
                            '. $this->langFile[$this->pageName]->title_field_description .'
                        </div>
                        <div class="input descript comment-input share-bloc-text editable-content desc-input" placeholder="'. $this->langFile[$this->pageName]->placeholder_give_more_details .'" spellcheck="false" contenteditable="true" id="edit-company-descript" spellcheck="false">'.$this->description .'</div>
                    </div>

                    <div class="update-button-container">  
                        <div class="loader-container loader-bt" data-elem="loader-bt">
                            <div class="loader-double-container">
                                <span class="loader loader-double">
                                </span>
                            </div>
                        </div>                 
                        <button class="share-button bt share-button-big" id="update-company">
                            '. $this->langFile[$this->pageName]->bt_myprofile_updatebutton .'                            
                        </button>
                    </div>
                </div>';
    }

    public function showMyCompany()
    {
        //test du logo: default ou non
        switch($this->logo){
            case 'default':
                $logo = '<img src="public/img/default/company.png" alt="WorldEsport Team logo">';
                break;
            default:
                $logo = '<img src="inc/img/imgcompany.php?imgname='. $this->logo .'&u='. $this->currentUser->pk_iduser .'" alt="WorldEsport Company logo">';
        }

        //test du currentplayhere, false or true
        switch($this->currentlyWorkHere){
            case true:
                $currentWorkHereContent = ' <div class="info-line current-work">
                                                <p>'. $this->langFile[$this->pageName]->title_field_myprofile_employee_currentlywork .'</p>
                                            </div>';
                $currentWorkTime = $this->langFile[$this->pageName]->word_now;
                break;
            default:
                $currentWorkHereContent = '';
                $currentWorkTime = $this->monthEnd .' '.$this->yearEnd;
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
                $description = '<div class="info-line collapse col-md-12" id="collapse-'. str_replace($this->unauthorizedChar,'',$this->name) .'">
                                    <p class="info-decription">'. $this->description .'</p>
                                </div>';
                $btcollapse = '';
        }

        //content
        $content = '<div class="profile-elem profile-career-container col-md-12" data-elem="'.$this->id.'">
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
                                        <p class="info">'. $this->jobtitle .' '. $this->langGenerals->word_at .'</p><p class="info">'. $this->name .'</p>
                                    </div>
                                    <div class="info-line">
                                        <p class="info">'. $this->city .',</p><p class="info"> '. $this->country .'</p>
                                    </div> 
                                    <div class="info-line">
                                        <p class="info">'. $this->monthStart .' '. $this->yearStart .'</p><p class="info"> - '. $currentWorkTime .'</p>
                                    </div>                                  
                                    '. $currentWorkHereContent .'
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

    public function showUserCompany()
    {
        //test du logo: default ou non
        switch($this->logo){
            case 'default':
                $logo = '<img src="public/img/default/company.png" alt="WorldEsport company logo">';
                break;
            default:
                if($this->userToDisplay)
                {
                    $logo = '<img src="inc/img/imgcompany.php?imgname='. $this->logo .'&u='. $this->userToDisplay->pk_iduser .'" alt="'. $this->name .' logo">';
                }
                else{
                    $logo = '<img src="inc/img/imgcompany.php?imgname='. $this->logo .'&u='. $this->currentUser->pk_iduser .'" alt="'. $this->name .' logo">';
                }
                break;
        }

        //test du currentplayhere, false or true
        switch($this->currentlyWorkHere){
            case true:
                $currentWorkHereContent = ' <div class="info-line current-work">
                                                <p>'. $this->langFile[$this->pageName]->text_currently_work_here .'</p>
                                            </div>';
                $currentWorkTime = $this->langFile[$this->pageName]->word_now;
                break;
            default:
                $currentWorkHereContent = '';
                $currentWorkTime = $this->monthEnd .' '.$this->yearEnd;
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
                $description = '<div class="info-line collapse col-md-12" id="collapse-'. str_replace($this->unauthorizedChar,'',$this->name) .'">
                                    <p class="info-decription">'. $this->description .'</p>
                                </div>';
                $btcollapse = '';
        }

        //content
        $content = '<div class="profile-elem profile-career-container col-md-12" data-elem="'.$this->id.'">
                        <div class="profile-aside-container">                            
                            <div class="profile-bloc-elem-left col-md-9">
                                <div class="infos-container col-md-12">
                                    <div class="info-line">
                                        <p class="info">'. $this->jobtitle .' '. $this->langGenerals->word_at .'</p><p class="info">'. $this->name .'</p>
                                    </div>
                                    <div class="info-line">
                                        <p class="info">'. $this->city .',</p><p class="info"> '. $this->country .'</p>
                                    </div> 
                                    <div class="info-line">
                                        <p class="info">'. $this->monthStart .' '. $this->yearStart .'</p><p class="info"> - '. $currentWorkTime .'</p>
                                    </div>                                  
                                    '. $currentWorkHereContent .'
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
            return $this->showMyCompany();
        }
        if(Session::getInstance()->read('current-state')['state'] == 'viewer')
        {
            return $this->showUserCompany();
        }
    }
}