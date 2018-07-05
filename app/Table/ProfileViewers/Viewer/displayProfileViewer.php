<?php

namespace app\Table\ProfileViewers\Viewer;

use app\Table\appDates;
use app\Table\AppDisplayElem;
use app\Table\ProfileViewers\displayProfileViewers;
use app\Table\UserModel\UserModel;
use core\Session\Session;

use app\Table\Images\Images\ImagesUsers\displayUsersImages;

class displayProfileViewer extends displayProfileViewers
{
    //USER ATTRIBUTES
    private $id;
    private $complete_name;
    private $current_team;
    private $previous_team;
    private $role;
    private $game;
    private $platform;
    private $current_company;
    private $jobtitle;
    private $slug;

    private $firstname;
    private $nickname;
    private $lastname;

    //DATES
    private $date;
    private $dateTransformed;
    private $time;

    //LINES TO DISPLAY CONTACTS INFOS
    private $lineJob;
    private $lineRole;

    //USEFULL OBJECTS
    private $UserImages;

    //OTHER VARIABLES
    private $todisplay; // = ProfileViewers pour display un element de la page de viewers || UmViewers pou display un element de menu

    public function __construct($user = false, $todisplay = false)
    {
        parent::__construct();

        //user id
        $this->id =                 (empty($user->fk_iduser))           ? '' : $user->fk_iduser;
        $this->date =               (empty($user->date))                ? '' : $user->date;
        $this->complete_name =      (empty($user->complete_name))       ? '' : $user->complete_name;
        $this->current_team =       (empty($user->current_team))        ? '' : $user->current_team;
        $this->previous_team =      (empty($user->previous_team))       ? '' : $user->previous_team;
        $this->role =               (empty($user->role))                ? '' : $user->role;
        $this->game =               (empty($user->game))                ? '' : $user->game;
        $this->platform =           (empty($user->platform))            ? '' : $user->platform;
        $this->current_company =    (empty($user->current_company))     ? '' : $user->current_company;
        $this->jobtitle =           (empty($user->jobtitle))            ? '' : $user->jobtitle;

        //DEFINITION DES INFOS A AFFICHER
        //si l'user possède un job courant
        if($user)
        {
            if($user->current_company)
            {
                $this->lineJob = $user->jobtitle.' '.$this->langGenerals->word_at.' '.$user->current_company;
            }
            else{
                $this->lineJob = '<span class="bold">'.$this->langGenerals->title_infoLine_nationnality.'</span> '.$user->nationnality;
            }

            //si l'user possède une current team
            if($user->current_team)
            {
                $this->lineRole = $user->role.' '.$this->langGenerals->word_on.' '.$user->game.' '.$this->langGenerals->word_at.' '.$user->current_team;
            }
            else{
                if($user->previous_team)
                {
                    $this->lineRole = '<span class="bold">'.$this->langGenerals->title_infoLine_previous_team.'</span> '.$user->previous_team;
                }
                else{
                    $this->lineRole = '<span class="bold">'.$this->langGenerals->title_infoLine_languages.'</span> '.$user->language;
                }
            }
        }

        //get name, nickname, firstname from complete_name
        $pieces = explode('"', $this->complete_name);
        $this->firstname =  str_replace(' ', '',$pieces[0]);
        $this->lastname =   str_replace(' ', '',$pieces[2]);
        $this->nickname =   $pieces[1];

        //get slug from iduser
        $model = new UserModel();
        $this->slug = $model->getSlugFromId($this->id);

        //format date:
        $appDates = new appDates($this->date);
        $this->dateTransformed = $appDates->getDate();

        //todisplay
        $this->todisplay = $todisplay;

        //images
        $this->UserImages = new displayUsersImages(false, $this->id);
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
        $role = $this->role ? $this->role .' '. $this->langGenerals->word_on .' '.$this->game . ' - ' . $this->platform .' '. $this->langGenerals->word_at .' '. $this->current_team : $this->previous_team;
        $job =  $this->current_company ? $this->jobtitle .' '. $this->langGenerals->word_at .' '. $this->current_company : '';

        return '<div class="contact-infos-container col-md-7">                   
                    <div class="contact-infos">
                        <h1 class="complete-name">'. $this->complete_name .'</h1>
                        <h3 class="role">'. $role .'</h3>
                        <h3 class="job">'. $job .'</h3>
                    </div>
                </div>';
    }

    public function showRightPart()
    {
        //old: <span class="bold">'. $this->time .'</span>
        return '<div class="time col-md-2">
                    <span class="bold">'. $this->dateTransformed .'</span>                                      
                </div>';
    }

    public function showBody()
    {
        return '<div class="date-mobile mobile">'. $this->showRightPart() .'</div>'. $this->showLeftPart() . $this->showMiddlePart() . '<div class="time-pc pc">'. $this->showRightPart() .'</div>';
    }


    public function showViewer()
    {
        return '<div class="contact-container col-md-12">
                    '. $this->showBody() .'
                </div>';
    }

    public function showUmViewer()
    {
        return '<div class="under-menu-item col-md-12">
                    <div class="user-infos-container col-md-12">
                        <div class="um-pic-container col-md-3">
                            <div class="pic um-pic">
                                <a href="index.php?p=profile&u='. $this->slug .'">'. $this->UserImages->showProfileUserPic_little() .'</a>
                            </div>
                        </div>
                        <div class="users-ids col-md-9">
                            <h4>'. $this->nickname .'</h4>
                            <h5>'. $this->firstname .'  '. $this->lastname .'</h5>
                        </div>
                    </div>
                </div>';
    }

    public function show()
    {
        if($this->id) //#todo to remove, juste au cas ou
        {
            if(Session::getInstance()->read('current-state')['state'] == 'owner')
            {
                if($this->todisplay == 'ProfileViewers')
                {
                    return $this->showViewer();
                }
                if($this->todisplay == 'UmViewers')
                {
                    return $this->showUmViewer();
                }

            }
            if(Session::getInstance()->read('current-state')['state'] == 'viewer')
            {
                if($this->todisplay == 'ProfileViewers')
                {
                    return $this->showViewer();
                }
                if($this->todisplay == 'UmViewers')
                {
                    return $this->showUmViewer();
                }
            }
        }

    }
}