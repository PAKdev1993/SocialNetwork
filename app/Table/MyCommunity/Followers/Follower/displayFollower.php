<?php

namespace app\Table\MyCommunity\Followers\Follower;

use app\Table\AppDisplay;

use app\Table\MyCommunity\Followers\displayFollowers;
use app\Table\Profile\Quickinfos\QuickInfosModel;

use app\Table\UserModel\displayUser;
use app\Table\UserModel\UserModel;

use app\Table\Images\Images\ImagesUsers\displayUsersImages;

class displayFollower extends displayFollowers
{
    private $id;
    private $iduser;
    private $iduserfollowed;
    private $date;

    protected $langFile;
    protected $langGenerals;
    protected $pageName;

    private $follower;
    private $user_profile_pic;

    private $lineJob;
    private $lineRole;

    private $UserImages;

    public function __construct($follower)
    {
        parent::__construct();
        $this->id =             $follower->pk_idfolowEntry;
        $this->iduser =         $follower->fk_iduser;
        $this->iduserfollowed = $follower->id_userfolowed;
        $this->date =           $follower->date;
        $this->slug =           $follower->slug;

        $this->follower =       $follower;
        $qiModel =      new QuickInfosModel();

        //#todo peux etre aller chezrche les information depuis la table quickinformation plutot que par la table user
        //#todo VERY IMPORTANT AJOUTER LA PLATFORM AU QUICK INFOS
        //affect infos to fdisplay block follower
        $qiFollower = $qiModel->getQuickInfosFromIdUser($this->iduser);

        //DEFINITION DES INFOS A AFFICHER
        //si l'user possède un job courant
        if($qiFollower->current_company)
        {
            $this->lineJob = $qiFollower->jobtitle.' '.$this->langGenerals->word_at.' '.$qiFollower->current_company;
        }
        else{
            $this->lineJob = '<span class="bold">'.$this->langGenerals->title_infoLine_nationnality.'</span> '.$qiFollower->nationnality;
        }

        //si l'user possède une current team
        if($qiFollower->current_team)
        {
            $this->lineRole = $qiFollower->role.' '.$this->langGenerals->word_on.' '.$qiFollower->game.' '.$this->langGenerals->word_at.' '.$qiFollower->current_team;
        }
        else{
            if($qiFollower->previous_team)
            {
                $this->lineRole = '<span class="bold">'.$this->langGenerals->title_infoLine_previous_team.'</span> '.$qiFollower->previous_team;
            }
            else{
                $this->lineRole = '<span class="bold">'.$this->langGenerals->title_infoLine_languages.'</span> '.$qiFollower->language;
            }
        }

        //get user profiile pic
        $model = new UserModel();
        $user = $model->getUserFromId($this->iduser);
        $displayUser = new displayUser($user);
        $this->user_profile_pic = $displayUser->showMyProfilePic();

        //images
        $this->UserImages = new displayUsersImages($user);
    }

    public function showEdit()
    {

    }
    
    public function showLeftPart()
    {
        return '<div class="community-elem-left-part col-md-3">
                    <div class="contact-pic-container">
                        <div class="contact-pic pic">
                            <a href=index.php?p=profile&u='. $this->follower->slug .'>'. $this->UserImages->showProfileUserPic_little() .'</a>
                        </div>
                    </div>
                </div>';
    }

    public function showMiddlePart()
    {
       return '<div class="community-elem-middle-part col-md-8">
                    <div class="contact-infos-container col-md-12">
                        <div class="contact-infos">
                            <h1 class="complete-name">'. $this->follower->firstname .' "'. $this->follower->nickname .'" '. $this->follower->lastname .'</h1>
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
                        
                    </div>
                </div>';
    }

    public function show()
    {
        return '<div class="community-elem col-md-12">
                    '. $this->showLeftPart() .'
                    '. $this->showMiddlePart() .'
                    '. $this->showRightPart() .'
                </div>';
    }
}