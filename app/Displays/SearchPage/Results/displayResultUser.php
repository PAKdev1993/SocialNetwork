<?php

namespace app\Displays\SearchPage\Results;

use app\Table\AppDisplay;
use core\Session\Session;

use app\Table\Images\Images\ImagesUsers\displayUsersImages;

class displayResultUser extends AppDisplay
{
    private $id;
    private $nickname;
    private $firstname;
    private $lastname;
    private $background_cover;
    private $background_profile;
    private $slug;
    private $current_team;
    private $role;
    private $game;
    private $platform;
    private $previous_team;
    private $current_company;
    private $jobtitle;
    private $location;

    private $UserImages;

    private $todisplay; // = searchPage: display search page result user || = searchBar: display search bar result
    private $result;

    public function __construct($result, $todisplay)
    {
        parent::__construct();

        $this->id =                 (empty($result->pk_iduser))           ? '' : $result->pk_iduser;
        $this->nickname =           (empty($result->nickname))            ? '' : $result->nickname;
        $this->firstname =          (empty($result->firstname))           ? '' : $result->firstname;
        $this->lastname =           (empty($result->lastname))            ? '' : $result->lastname;
        $this->background_cover =   (empty($result->background_cover))    ? '' : $result->background_cover;
        $this->background_profile = (empty($result->background_profile))  ? '' : $result->background_profile;
        $this->slug =               (empty($result->slug))                ? '' : $result->slug;
        $this->current_team =       (empty($result->current_team))        ? '' : $result->current_team;
        $this->previous_team =      (empty($result->previous_team))       ? '' : $result->previous_team;
        $this->role =               (empty($result->role))                ? '' : $result->role;
        $this->game =               (empty($result->game))                ? '' : $result->game;
        $this->platform =           (empty($result->platform))            ? '' : $result->platform;
        $this->current_company=     (empty($result->current_company))     ? '' : $result->current_company;
        $this->jobtitle =           (empty($result->jobtitle))            ? '' : $result->jobtitle;
        $this->location =           (empty($result->location))            ? '' : $result->location;

        $this->todisplay =  $todisplay;
        $this->result =     $result;

        //images
        $this->UserImages = new displayUsersImages($result);
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
        $role = $this->role ? $this->role . ' on ' . $this->game . ' - ' . $this->platform . ' at ' . $this->current_team : $this->previous_team;
        $job =  $this->current_company ? $this->jobtitle . ' at ' . $this->current_company : '';

        return '<div class="contact-infos-container col-md-7">
                    <div class="contact-infos">
                        <h1 class="complete-name">'. $this->firstname .' "'. $this->nickname .'" '. $this->lastname .'</h1>
                        <h3 class="role">'. $role .'</h3>
                        <h3 class="job">'. $job .'</h3>
                    </div>
                </div>';
    }

    public function showRightPart()
    {
        return '';
    }

    public function showResultUserSearchPage()
    {
        return '<div class="contact-container col-md-12">
                    '. $this->showLeftPart() . $this->showMiddlePart() . $this->showRightPart() .'
                </div>';
    }

    public function showResultUserSearchBar()
    {
        return '<div class="searchbar-result col-md-12">
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
        if($this->todisplay == 'searchBar')
        {
            return $this->showResultUserSearchBar();
        }
        if($this->todisplay == 'searchPage')
        {
            return $this->showResultUserSearchPage();
        }

    }
}