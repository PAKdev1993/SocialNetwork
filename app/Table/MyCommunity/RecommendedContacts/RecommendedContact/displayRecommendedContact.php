<?php

namespace app\Table\MyCommunity\RecommendedContacts\RecommendedContact;

use app\Table\AppDisplay;
use app\Table\MyCommunity\RecommendedContacts\displayRecommendedContacts;
use core\Session\Session;

use app\Table\Profile\Quickinfos\QuickInfosModel;
use app\Table\Images\Images\ImagesUsers\displayUsersImages;

class displayRecommendedContact extends displayRecommendedContacts
{
    private $id;
    private $nickname;
    private $firstname;
    private $lastname;
    private $email;
    private $gamersummary;
    private $employeesummary;
    private $interests;
    private $hireme;
    private $recruitme;
    private $background_cover;
    private $background_profile;
    private $slug;

    protected $langFile;
    protected $langGenerals;
    protected $langInfosBulles;

    private $lineJob;
    private $lineRole;

    private $userQuckinfos;
    private $UserImages;

    public function __construct($user = false, $iduser = false)
    {
        parent::__construct();
        if($user)
        {
            $this->id =                 (empty($user->pk_iduser))           ? '' : $user->pk_iduser;
            $this->nickname =           (empty($user->nickname))            ? '' : $user->nickname;
            $this->firstname =          (empty($user->firstname))           ? '' : $user->firstname;
            $this->lastname =           (empty($user->lastname))            ? '' : $user->lastname;
            $this->email =              (empty($user->email))               ? '' : $user->email;
            $this->gamersummary =       (empty($user->gamersummary))        ? '' : $user->gamersummary;
            $this->employeesummary =    (empty($user->employeesummary))     ? '' : $user->employeesummary;
            $this->interests =          (empty($user->interests))           ? '' : explode('/', $user->interests);
            $this->hireme =             (empty($user->hireme))              ? '' : $user->hireme;
            $this->recruitme =          (empty($user->recruitme))           ? '' : $user->recruitme;
            $this->background_cover =   (empty($user->background_cover))    ? '' : $user->background_cover;
            $this->background_profile = (empty($user->background_profile))  ? '' : $user->background_profile;
            $this->slug =               (empty($user->slug))                ? '' : $user->slug;

            $qiModel =                  new QuickInfosModel();
            $this->userQuckinfos =      $qiModel->getQuickInfosFromIdUser($this->id);

            //#todo refactoriser ds une fonction
            //DEFINITION DES INFOS A AFFICHER
            //si l'user possède un job courant
            if($this->userQuckinfos->current_company)
            {
                $this->lineJob = $this->userQuckinfos->jobtitle.' '.$this->langGenerals->word_at.' '.$this->userQuckinfos->current_company;
            }
            else{
                $this->lineJob = '<span class="bold">'.$this->langGenerals->title_infoLine_nationnality.'</span> '.$this->userQuckinfos->nationnality;
            }

            //si l'user possède une current team
            if($this->userQuckinfos->current_team)
            {
                $this->lineRole = $this->userQuckinfos->role.' '.$this->langGenerals->word_on.' '.$this->userQuckinfos->game.' '.$this->langGenerals->word_at.' '.$this->userQuckinfos->current_team;
            }
            else{
                if($this->userQuckinfos->previous_team)
                {
                    $this->lineRole = '<span class="bold">'.$this->langGenerals->title_infoLine_previous_team.'</span> '.$this->userQuckinfos->previous_team;
                }
                else{
                    $this->lineRole = '<span class="bold">'.$this->langGenerals->title_infoLine_languages.'</span> '.$this->userQuckinfos->language;
                }
            }

            //images
            $this->UserImages = new displayUsersImages($user);
        }
        else{
            $this->id = $iduser;
        }
    }

    public function showEdit()
    {
        //
    }
    
    public function showLeftPart()
    {
        return '<div class="contact-pic-container col-md-3">
                    <div class="contact-pic pic">
                        <a href="index.php?p=profile&u='. $this->slug .'">'. $this->UserImages->showProfileUserPic_little() .'</a>
                    </div>
                </div>';
    }

    public function showMiddlePart()
    {
        return '<div class="contact-infos-container col-md-7">
                    <div class="contact-infos">
                        <h1 class="complete-name">'. $this->firstname .' "'. $this->nickname .'" '. $this->lastname .'</h1>
                        <h3 class="role">'. $this->lineRole .'</h3>
                        <h3 class="job">'. $this->lineJob .'</h3>
                    </div>
                </div>';
    }

    public function showRightPart()
    {
        return '<div class="add-container col-md-2">
                    <button class="bt-add bt-icon-2 infobulle" data-add="'. $this->id .'">
                        <span class="message"><span>'. $this->langInfosBulles->action_addto_contactList .'</span></span>
                    </button>
                </div>';
    }

    public function showBody()
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

    public function showContactAdded()
    {
        return '<div class="confirm-container col-md-12">                       
                    <div class="cancel-bt-container col-md-3"> 
                        <button class="cancel bt" data-cancel-add="'. $this->id .'">
                            '. $this->langGenerals->word_cancel .'
                        </button>
                    </div>
                    <div class="cancel-title-container col-md-6">
                        <h2>'. $this->langFile[$this->pageName]->text_request_sent .'</h2>
                    </div>
                </div>';
    }

    public function showRecommendedContact()
    {
        return '<div class="contact-container col-md-12">
                    '. $this->showBody() .'
                </div>';
    }

    public function show()
    {
        if($this->id) //#todo to remove, juste au cas ou
        {
            if(Session::getInstance()->read('current-state')['state'] == 'owner')
            {
                return $this->showRecommendedContact();
            }
            //cas des pages aboutus/coming soon etc
            if(Session::getInstance()->read('current-state')['state'] == 'viewer')
            {
                return $this->showRecommendedContact();
            }
        }
    }
}