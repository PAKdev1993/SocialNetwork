<?php

namespace app\Table\UserModel;

use core\Session\Session;
use app\Table\AppDisplay;

class displayUser extends AppDisplay
{
    private $id;
    private $nickname;
    private $firstname;
    private $lastname;
    private $email;
    private $langWebsite;
    private $gamersummary;
    private $employeesummary;
    private $interests;
    private $hireme;
    private $recruitme;
    private $background_cover;
    private $background_profile;
    private $slug;

    protected $pageName;

    private $todisplay; //interests || gamer.summarry || employee.summary

    private $maxInterestsSize = 6;

    public function __construct($user, $todisplay = false)
    {
        $this->pageName = 'profile';
        parent::__construct(false, $this->pageName);
        $this->id =                 (empty($user->pk_iduser))           ? '' : $user->pk_iduser;
        $this->nickname =           (empty($user->nickname))            ? '' : $user->nickname;
        $this->firstname =          (empty($user->firstname))           ? '' : $user->firstname;
        $this->lastname =           (empty($user->lastname))            ? '' : $user->lastname;
        $this->email =              (empty($user->email))               ? '' : $user->email;
        $this->langWebsite =        (empty($user->langWebsite))         ? '' : $user->langWebsite;
        $this->gamersummary =       (empty($user->gamersummary))        ? '' : $user->gamersummary;
        $this->employeesummary =    (empty($user->employeesummary))     ? '' : $user->employeesummary;
        $this->interests =          (empty($user->interests))           ? '' : explode('/', $user->interests);
        $this->hireme =             (empty($user->hireme))              ? '' : $user->hireme;
        $this->recruitme =          (empty($user->recruitme))           ? '' : $user->recruitme;
        $this->background_cover =   (empty($user->background_cover))    ? '' : $user->background_cover;
        $this->background_profile = (empty($user->background_profile))  ? '' : $user->background_profile;
        $this->slug =               (empty($user->slug))                ? '' : $user->slug;

        $this->todisplay = $todisplay;
    }

    //summary displayed when the user didnt fill anithing of his summary
    public function showSumEditFt()
    {
        return ' <div class="bloc-edit-container-permanent">
                    <div class="loader-container loader-profile-elem" id="loader-summary-body" data-elem="loader-summary-body">
                        <div class="loader loader-double">
                        </div>
                    </div>
                    <div class="field-container col-md-12">
                        <div class="label-field">
                            '. $this->langFile[$this->pageName]->title_field_myprofile_gamer_summary .'
                        </div>
                        <div class="input descript comment-input share-bloc-text editable-content desc-input" placeholder="'. $this->langFile[$this->pageName]->error_myprofile_summaryfail .'" spellcheck="false" contenteditable="true" id="add-summary" data-elem="add-summary-input">'. $this->gamersummary .'</div>
                        <div class="bulle-error">
                            <span class="message message-top"><span>'. $this->langFile[$this->pageName]->error_myprofile_summaryfail .'</span></span>
                            <span class="pseudo"></span>
                        </div> 
                    </div>
                    
                    <div class="update-button-container">
                        <button class="share-button bt share-button-big" id="update-gamer-summary" data-action="update-gamer-summary">'. $this->langFile[$this->pageName]->bt_myprofile_updatebutton .'</button>
                    </div>
                 </div>';
    }

    public function showSumEdit()
    {
        return ' <div class="bloc-edit-container">
                    <div class="loader-container loader-profile-elem" id="loader-summary-body" data-elem="loader-summary-body">
                        <div class="loader loader-double">
                        </div>
                    </div>
                    <div class="field-container col-md-12">
                        <div class="label-field">
                            '. $this->langFile[$this->pageName]->title_field_myprofile_gamer_summary .'
                        </div>
                        <div class="input descript comment-input share-bloc-text editable-content desc-input" placeholder="'. $this->langFile[$this->pageName]->placeholder_myprofile_summary_addfewlines .'" spellcheck="false" contenteditable="true" id="add-summary" data-elem="add-summary-input">'. $this->gamersummary .'</div>
                        <div class="bulle-error">
                            <span class="message message-top"><span>'. $this->langFile[$this->pageName]->error_myprofile_summaryfail .'</span></span>
                            <span class="pseudo"></span>
                        </div> 
                    </div>
                    
                    <div class="update-button-container">
                        <button class="share-button bt share-button-big" id="update-gamer-summary" data-action="update-gamer-summary">'. $this->langFile[$this->pageName]->bt_myprofile_updatebutton .'</button>
                    </div>
                 </div>';
    }

    public function showSumEmployeeEdit()
    {
        return ' <div class="bloc-edit-container">
                    <div class="loader-container loader-profile-elem" id="loader-summary-body" data-elem="loader-summary-body">
                        <div class="loader loader-double">
                        </div>
                    </div>
                    <div class="field-container col-md-12">
                        <div class="label-field">
                            '. $this->langFile[$this->pageName]->title_field_myprofile_employee_summary .'
                        </div>
                        <div class="input descript comment-input share-bloc-text editable-content desc-input" placeholder="'. $this->langFile[$this->pageName]->placeholder_myprofile_summary_addfewlines .'" spellcheck="false" contenteditable="true" id="add-summary" data-elem="add-summary-input">'. $this->employeesummary .'</div>
                        <div class="bulle-error">
                            <span class="message message-top"><span>'. $this->langFile[$this->pageName]->error_myprofile_summaryfail .'</span></span>
                            <span class="pseudo"></span>
                        </div> 
                    </div>
                    
                    <div class="update-button-container">
                        <button class="share-button bt share-button-big" id="update-employee-summary" data-action="update-employee-summary">'. $this->langFile[$this->pageName]->bt_myprofile_updatebutton .'</button>
                    </div>
                 </div>';
    }
    
    public function showSumEmployeeEditFt()
    {
        return ' <div class="bloc-edit-container-permanent">
                    <div class="loader-container loader-profile-elem" id="loader-summary-body" data-elem="loader-summary-body">
                        <div class="loader loader-double">
                        </div>
                    </div>
                    <div class="field-container col-md-12">
                        <div class="label-field">
                            '. $this->langFile[$this->pageName]->title_field_myprofile_employee_summary .'
                        </div>
                        <div class="input descript comment-input share-bloc-text editable-content desc-input" placeholder="'. $this->langFile[$this->pageName]->placeholder_myprofile_summary_addfewlines .'" spellcheck="false" contenteditable="true" id="add-summary" data-elem="add-summary-input">'. $this->employeesummary .'</div>
                        <div class="bulle-error">
                            <span class="message message-top"><span>'. $this->langFile[$this->pageName]->error_myprofile_summaryfail .'</span></span>
                            <span class="pseudo"></span>
                        </div> 
                    </div>
                    
                    <div class="update-button-container">
                        <button class="share-button bt share-button-big" id="update-employee-summary" data-action="update-employee-summary">'. $this->langFile[$this->pageName]->bt_myprofile_updatebutton .'</button>
                    </div>
                 </div>';
    }
    
    public function showMySumm()
    {
        return '<div class="bloc-container">
                    <div class="loader-container loader-profile-elem loader-profile-elem-body" id="loader-summary-body" data-elem="loader-summary-body">
                        <div class="loader loader-double">
                        </div>
                    </div>
                    <p id="summary">'. $this->gamersummary .'</p>
                </div>';
    }

    public function showUserSumm()
    {
        return '<div class="bloc-container">                   
                    <p id="summary">'. $this->gamersummary .'</p>
                </div>';
    }

    public function showUserSumEmpty()
    {
        return '<div class="bloc-container empty">
                    <div class="message-empty-container">
                        <p><span class="bold">'. $this->nickname .'</span> '. $this->langFile[$this->pageName]->title_myprofile_emptycontent_gamersummary .'</p>
                    </div>                    
                </div>';
    }

    public function showMySumEmployee()
    {
        return '<div class="bloc-container">
                    <div class="loader-container loader-profile-elem loader-profile-elem-body" id="loader-summary-body" data-elem="loader-summary-body">
                        <div class="loader loader-double">
                        </div>
                    </div>
                    <p id="summary">'. $this->employeesummary .'</p>
                </div>';
    }

    public function showUserSumEmployee()
    {
        return '<div class="bloc-container">                   
                    <p id="summary">'. $this->employeesummary .'</p>
                </div>';
    }

    public function showUserSumEmployeeEmpty()
    {
        return '<div class="bloc-container empty">
                    <div class="message-empty-container">
                        <p><span class="bold">'. $this->nickname .'</span> '. $this->langFile[$this->pageName]->title_myprofile_emptycontent_employeesummary .'</p>
                    </div>                    
                </div>';
    }

    public function showIntEdit()
    {
        $content = '';
        //l'user a deja rempli ses interests
        if($this->interests)
        {
            for($i = 0; $i < $this->maxInterestsSize; $i++)
            {
                $num = $i + 1;
                //cas du premier $i -> bulle error
                if($i == 0)
                {
                    $content = $content . ' <div class="field-container interest-container col-md-6">
                                                <div class="label-field label-interest">
                                                    '. $num .' :                            
                                                </div>
                                                <div class="input largel comment-input" contenteditable="true" spellcheck="false">'. $this->interests[$i] .'</div>
                                                <div class="bulle-error">
                                                    <span class="message message-top"><span>'. $this->langFile[$this->pageName]->error_myprofile_interests_interestsfail .'</span></span>
                                                    <span class="pseudo"></span>
                                                </div>
                                            </div>';
                }
                else{
                    $content = $content . ' <div class="field-container interest-container col-md-6">
                                                <div class="label-field label-interest">
                                                    '. $num .' :                            
                                                </div>
                                                <div class="input largel comment-input" contenteditable="true" spellcheck="false">'. $this->interests[$i] .'</div>                                               
                                            </div>';
                }
            }
        }
        //l'user n'rempli ses interests
        else{
            for($i = 0; $i < $this->maxInterestsSize; $i++)
            {
                $num = $i + 1;
                //cas du premier $i -> bulle error
                if($i == 0)
                {
                    $content = $content . ' <div class="field-container interest-container col-md-6">
                                                <div class="label-field label-interest">
                                                    '. $num .' :                            
                                                </div>
                                                <div class="input largel comment-input" contenteditable="true" spellcheck="false"></div>
                                                <div class="bulle-error">
                                                    <span class="message message-top big"><span>'. $this->langFile[$this->pageName]->error_myprofile_interests_interestsfail .'</span></span>
                                                    <span class="pseudo"></span>
                                                </div>
                                            </div>';
                }
                else{
                    $content = $content . ' <div class="field-container interest-container col-md-6">
                                                <div class="label-field label-interest">
                                                    '. $num .' :                            
                                                </div>
                                                <div class="input largel comment-input" contenteditable="true" spellcheck="false"></div>
                                            </div>';
                }
            }
        }

        return ' <div class="bloc-edit-container">
                    <div class="loader-container loader-profile-elem loader-profile-elem-body" id="loader-interests-body" data-elem="loader-interests-body">
                        <div class="loader loader-double">
                        </div>
                    </div>
                    '. $content .'
                    <div class="update-button-container">                       
                        <button class="share-button bt share-button-big" id="update-interests" data-action="update-interests">'. $this->langFile[$this->pageName]->bt_myprofile_updatebutton .'</button>
                    </div>                  
                </div>';
    }

    public function showIntEditFt()
    {
        $content = '';
        for($i = 0; $i < $this->maxInterestsSize; $i++)
        {
            $num = $i + 1;
            //cas du premier $i -> bulle error
            if($i == 0)
            {
                $content = $content . ' <div class="field-container interest-container col-md-6">
                                            <div class="label-field label-interest">
                                                '. $num .' :                            
                                            </div>
                                            <div class="input largel comment-input" contenteditable="true" spellcheck="false"></div>
                                            <div class="bulle-error">
                                                <span class="message message-top big"><span>'. $this->langFile[$this->pageName]->error_myprofile_interests_interestsfail .'</span></span>
                                                <span class="pseudo"></span>
                                            </div>
                                        </div>';
            }
            else{
                $content = $content . ' <div class="field-container interest-container col-md-6">
                                            <div class="label-field label-interest">
                                                '. $num .' :                            
                                            </div>
                                            <div class="input largel comment-input" contenteditable="true" spellcheck="false"></div>
                                        </div>';
            }
        }

        return ' <div class="bloc-edit-container-permanent">
                    <div class="loader-container loader-profile-elem loader-profile-elem-body" id="loader-interests-body" data-elem="loader-interests-body">
                        <div class="loader loader-double">
                        </div>
                    </div>
                    '. $content .'
                    <div class="update-button-container">                       
                        <button class="share-button bt share-button-big" id="update-interests" data-action="update-interests">'. $this->langFile[$this->pageName]->bt_myprofile_updatebutton .'</button>
                    </div>                  
                </div>';
    }

    public function showIntEmpty()
    {
        return '<div class="bloc-container empty">
                    <div class="message-empty-container">
                        <p><span class="bold">'. $this->nickname .'</span> '. $this->langFile[$this->pageName]->title_myprofile_emptycontent_interest .'</p>
                    </div>                    
                </div>';
    }

    public function showMyInterest()
    {
        $content = '';
        foreach($this->interests as $interest)
        {
            //pour parer au cas ou l'interest est vide, car il est systematiquement affiché 6 interest, min et max, certain peuvent etre vide
            if($interest != '')
            {
                $content = $content . '<div class="interest-line col-md-6">
                                            <p>'. $interest .'</p>
                                        </div>';
            }
        }
        return '<div class="bloc-container" id="interests-container">
                    <div class="loader-container loader-profile-elem loader-profile-elem-body" id="loader-interests-body">
                        <div class="loader loader-double">
                        </div>
                    </div>
                    '. $content .'            
                </div>';
    }

    public function showUserInterest()
    {
        $content = '';
        foreach($this->interests as $interest)
        {
            //pour parer au cas ou l'interest est vide, car il est systematiquement affiché 6 interest, min et max, certain peuvent etre vide
            if($interest != '')
            {
                $content = $content . '<div class="interest-line col-md-6">
                                            <p>'. $interest .'</p>
                                        </div>';
            }
        }
        return '<div class="bloc-container" id="interests-container">                    
                    '. $content .'            
                </div>';
    }

    public function showCoverPic() //#todo homogeiniser: cette fonction est inhomogène avecl e reste, il a été choisi de differencier les elements de l'user courant et ceux des user affichés par l'user courant grace a u prefixe My, changer tout ou homogeiniser cete fonction
    {
        if(empty($this->background_cover))
        {
            return '<img src="public/img/default/defaultcover.jpg" alt="WorldEsport default cover">';
        }
        else{
            return '<img src="inc/img/imgcover.php?imgname='. $this->background_cover .'&sl='. $this->slug .'" alt="'. $this->slug .' cover">';
        }
    }

    public function showMyProfilePic()
    {
        if(empty($this->background_profile))
        {
            return '<img src="public/img/default/defaultprofile.jpg" alt="WorldEsport default cover">';
        }
        else{
            return '<img src="inc/img/imgprofile.php?imgname='. $this->background_profile .'" alt="'. $this->slug .' picture">';
        }
    }

    public function showUserProfilePic()
    {
        if(empty($this->background_profile))
        {
            return '<img src="public/img/default/defaultprofile.jpg" alt="WorldEsport default cover">';
        }
        else{
            return '<img src="inc/img/imguser.php?sl='. $this->slug .'" alt="'. $this->slug .' picture">';
        }
    }

    public function showUserProfileLinkForChatBox($userCompleteName)
    {
        return "<a href='index.php?p=profile&u=". $this->slug ."'>".$userCompleteName."</a>";
    }

    public function show()
    {
        if(Session::getInstance()->read('current-state')['state'] == 'owner')
        {
            // DISPLAY GAMER SUMMARY
            if($this->todisplay == 'gamer.summary')
            {
                if(!$this->gamersummary)
                {
                    return $this->showMySummaryft($this->showSumEditFt());
                }
                else{
                    return $this->showMySummary($this->showMySumm());
                }
            }
            // DISPLAY EMPLOYEE SUMMARY
            if($this->todisplay == 'employee.summary')
            {
                if(!$this->employeesummary)
                {
                    return $this->showMyEmployeeSummaryft($this->showSumEmployeeEditFt());
                }
                else{
                    return $this->showMyEmployeeSummary($this->showMySumEmployee());
                }
            }
            if($this->todisplay == 'interests')
            {
                if(!$this->interests)
                {
                    return $this->showMyInterestsft($this->showIntEditFt());
                }
                else{
                    return $this->showMyInterests($this->showMyInterest());
                }
            }
        }
        if(Session::getInstance()->read('current-state')['state'] == 'viewer')
        {
            // DISPLAY USER GAMER SUMMARY
            if($this->todisplay == 'gamer.summary')
            {
                if(!$this->gamersummary)
                {
                    return $this->showUserSummaryEmpty($this->showUserSumEmpty());
                }
                else{
                    return $this->showUserSummary($this->showUserSumm());
                }
            }
            // DISPLAY USER EMPLOYEE SUMMARY
            if($this->todisplay == 'employee.summary')
            {
                if(!$this->employeesummary)
                {
                    return $this->showUserEmployeeSummaryEmpty($this->showUserSumEmployeeEmpty());
                }
                else{
                    return $this->showUserEmployeeSummary($this->showUserSumEmployee());
                }
            }
            if($this->todisplay == 'interests')
            {
                if(!$this->interests)
                {
                    return $this->showUserInterestsEmpty($this->showIntEmpty());
                }
                else{
                    return $this->showUserInterests($this->showUserInterest());
                }
            }
        }
    }
}