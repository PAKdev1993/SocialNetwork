<?php

namespace app\Table\MyCommunity\PendingContacts\PendingContact;

use app\Table\MyCommunity\PendingContacts\displayPendingContacts;
use app\Table\UserModel\displayUser;
use app\Table\UserModel\UserModel;

class displayPendingContact extends displayPendingContacts
{
    private $id;
    private $completeName;
    private $currentTeamQi;
    private $previousTeams;
    private $roleQi;
    private $gameQi;
    private $currentCompanyQi;
    private $jobtitleQi;

    private $lineJob;
    private $lineRole;

    protected $langFile;
    protected $langGenerals;
    protected $pageName;

    private $role;
    private $job;
    private $slugUser;
    private $user_profile_pic;

    // CETTE CLASSE A D'AVANTAGE BESOIN DES QUICK INFOS DE L'USER D'OU LE PASSAGE D'UN QI Object
    public function __construct($contactsQi = false)
    {
        parent::__construct();
        if($contactsQi)
        {
            //ces test sont réalisé car cette classe peux etre appelé avec un paramètre un utilisateur, comme dans inc/MyCommunity/block
            $this->id =                 $contactsQi->fk_iduser      ? $contactsQi->fk_iduser        : '';
            $this->completeName =       $contactsQi->complete_name  ? $contactsQi->complete_name    : '';
            $this->currentTeamQi =      $contactsQi->current_team   ? $contactsQi->current_team     : ''; //#todo HERE DISPLAY PREVIOUS TEAMS
            $this->previousTeams =      $contactsQi->previous_team  ? $contactsQi->previous_team    : '';
            $this->roleQi =             $contactsQi->role           ? $contactsQi->role             : '';
            $this->gameQi =             $contactsQi->game           ? $contactsQi->game             : '';
            $this->platformQi =         $contactsQi->platform       ? $contactsQi->platform         : '';
            $this->currentCompanyQi =   $contactsQi->current_company? $contactsQi->current_company  : '';
            $this->jobtitleQi =         $contactsQi->jobtitle       ? $contactsQi->jobtitle         : '';

            //DEFINITION DES INFOS A AFFICHER
            //si l'user possède un job courant
            if($contactsQi->current_company)
            {
                $this->lineJob = $contactsQi->jobtitle.' '.$this->langGenerals->word_at.' '.$contactsQi->current_company;
            }
            else{
                $this->lineJob = '<span class="bold">'.$this->langGenerals->title_infoLine_nationnality.'</span> '.$contactsQi->nationnality;
            }

            //si l'user possède une current team
            if($contactsQi->current_team)
            {
                $this->lineRole = $contactsQi->role.' '.$this->langGenerals->word_on.' '.$contactsQi->game.' '.$this->langGenerals->word_at.' '.$contactsQi->current_team;
            }
            else{
                if($contactsQi->previous_team)
                {
                    $this->lineRole = '<span class="bold">'.$this->langGenerals->title_infoLine_previous_team.'</span> '.$contactsQi->previous_team;
                }
                else{
                    $this->lineRole = '<span class="bold">'.$this->langGenerals->title_infoLine_languages.'</span> '.$contactsQi->language;
                }
            }
            
            //profile pic of user
            $model = new UserModel();
            $user = $model->getUserFromId($this->id);
            $displayUser = new displayUser($user);
            $this->user_profile_pic = $displayUser->showUserProfilePic();

            //user slug
            $this->slugUser = $model->getSlugFromId($this->id);
        }
    }

    public function showEdit()
    {

    }
    
    public function showLeftPart()
    {
        return '<div class="community-elem-left-part col-md-3">
                    <div class="contact-pic-container col-md-3">
                        <div class="contact-pic pic">
                            <a href="index.php?p=profile&u='. $this->slugUser .'">'. $this->user_profile_pic .'</a>
                        </div>
                    </div>
                </div>';
    }

    public function showMiddlePart()
    {
        return '<div class="community-elem-middle-part col-md-6">
                    <div class="contact-infos-container col-md-12">
                        <div class="contact-infos">
                            <h1 class="complete-name">'. $this->completeName .'</h1>
                            <h3 class="role">'. $this->lineRole .'</h3>
                            <h3 class="job">'. $this->lineJob .'</h3>
                        </div>                       
                    </div>
                </div>';
    }

    public function showRightPart()
    {
        return '<div class="community-elem-right-part col-md-3">
                    <div class="bt-more-container">                    
                   
                        <div class="bt-container">
                            <button class="share-button bt accept-user" data-accept-add="'.$this->id.'">
                                '. $this->langFile[$this->pageName]->action_accept_contact .'
                            </button>
                            <button class="share-button bt decline-user decline-add">
                                '. $this->langFile[$this->pageName]->action_decline_contact .'
                            </button>  
                            <div class="contact-added-message">
                                 <span></span>
                            </div>
                        </div>
                       
                        <div class="bt-confirm-container">
                            <div class="title-confirm">
                                <h1>'. $this->langGenerals->word_ask_sure .'</h1>
                            </div>
                            <button class="share-button bt accept-user" data-decline-add="'.$this->id.'">
                                '. $this->langGenerals->bt_y .'
                            </button>
                            <button class="share-button bt decline-user" data-action="back">
                               '. $this->langGenerals->bt_n .'
                            </button>
                        </div>
                        
                    </div>
                </div>';
    }

    public function showAddedMessage()
    {
        return '<div class="contact-added-message">
                     <span></span>
                </div>';
    }

    public function show()
    {
        return '<div class="loader-container loader-elem-bloc loader-profile-elem">
                    <div class="loader-double-container">
                        <span class="loader loader-double">
                        </span>
                    </div>
                </div>
                '. $this->showLeftPart() .'
                '. $this->showMiddlePart() .'
                '. $this->showRightPart() .'';
    }
}