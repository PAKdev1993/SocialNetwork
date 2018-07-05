<?php

namespace app\Table\MyCommunity\Contacts\Contact;

use app\App;

use app\Table\AppDisplay;

use app\Table\MyCommunity\Contacts\displayContacts;
use app\Table\Profile\Quickinfos\QuickInfosModel;

use app\Table\UserModel\displayUser;
use app\Table\UserModel\UserModel;

class displayContact extends displayContacts//#todo optimiser les extends des displayX et displayXs ne sont pas fait de manière homogène et peux clair, refaire ca mieux
{
    private $id;
    private $iduser;
    private $idcontact;
    private $blocked;
    private $date;

    protected $langFile;
    protected $langGenerals;

    private $contactUser;

    private $user_profile_pic;

    private $lineJob;
    private $lineRole;

    public function __construct($contact)
    {
        parent::__construct();
        //ces test sont réalisé car cette classe peux etre appelé avec un paramètre un utilisateur, comme dans inc/MyCommunity/block
        $this->id =         $contact->pk_contactEntry;
        $this->iduser =     $contact->fk_iduser;
        $this->idcontact =  $contact->id_contact;
        $this->blocked =    $contact->blocked;
        $this->date =       $contact->date;

        //test nescessaire car ce display peux etre appelé avec des paramètres dont les atrributs varie: inc/MyCommunity/block appel avec un contact, AppProfile avec un JOIN nentre contact et user
        if(property_exists($contact, 'pk_iduser'))
        {
            $this->contactUser = $contact;
        }
        else{
            $model = new UserModel(App::getDatabase());
            $this->contactUser = $model->getUserFromId($this->idcontact);
        }
        $qiModel = new QuickInfosModel();

        //#todo peux etre aller chezrche les information depuis la table quickinformation plutot que par la table user
        //#todo VERY IMPORTANT AJOUTER LA PLATFORM AU QUICK INFOS
        //affect infos to fdisplay block contact
        $qiContact = $qiModel->getQuickInfosFromIdUser($this->contactUser->pk_iduser);

        //DEFINITION DES INFOS A AFFICHER
        //si l'user possède un job courant
        if($qiContact->current_company)
        {
            $this->lineJob = $qiContact->jobtitle.' '.$this->langGenerals->word_at.' '.$qiContact->current_company;
        }
        else{
            $this->lineJob = '<span class="bold">'.$this->langGenerals->title_infoLine_nationnality.'</span> '.$qiContact->nationnality;
        }

        //si l'user possède une current team
        if($qiContact->current_team)
        {
            $this->lineRole = $qiContact->role.' '.$this->langGenerals->word_on.' '.$qiContact->game.' '.$this->langGenerals->word_at.' '.$qiContact->current_team;
        }
        else{
            if($qiContact->previous_team)
            {
                $this->lineRole = '<span class="bold">'.$this->langGenerals->title_infoLine_previous_team.'</span> '.$qiContact->previous_team;
            }
            else{
                $this->lineRole = '<span class="bold">'.$this->langGenerals->title_infoLine_languages.'</span> '.$qiContact->language;
            }
        }

        //profile pic of user
        $displayUser = new displayUser($this->contactUser);
        $this->user_profile_pic = $displayUser->showUserProfilePic();
    }

    public function showEdit()
    {

    }
    
    public function showLeftPart()
    {
        return '<div class="community-elem-left-part col-md-2">
                    <div class="contact-pic-container col-md-12">
                        <div class="contact-pic pic">
                            <a href="index.php?p=profile&u='. $this->contactUser->slug .'">'. $this->user_profile_pic .'</a>
                        </div>
                    </div>
                </div>';
    }

    public function showMiddlePart()
    {
        return '<div class="community-elem-middle-part col-md-7">
                    <div class="contact-infos-container col-md-12">
                        <div class="contact-infos">
                            <h1 class="complete-name">'. $this->contactUser->firstname .' "'. $this->contactUser->nickname .'" '. $this->contactUser->lastname .'</h1>
                            <h3 class="role">'. $this->lineRole .'</h3>
                            <h3 class="job">'. $this->lineJob .'</h3>
                        </div>
                    </div>
                </div>';
    }

    public function showRightPart()
    {
        if($this->blocked)
        {
            return '<div class="community-elem-right-part col-md-3">
                        <div class="bt-more-container">                       
                            <button class="share-button bt unblock-user-bt" data-blocked="'. $this->contactUser->pk_iduser .'">
                                '. $this->langFile[$this->pageName]->bt_unblock_user .'
                            </button>
                        </div>
                    </div>';
        }
        else{
            return '<div class="community-elem-right-part col-md-3">
                        <div class="bt-more-container">                       
                            <button class="share-button bt block-user-bt" data-blocked="'. $this->contactUser->pk_iduser .'">
                                '. $this->langFile[$this->pageName]->bt_block_user .'
                            </button>
                        </div>
                    </div>';
        }
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