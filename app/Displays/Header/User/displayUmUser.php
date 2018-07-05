<?php

namespace app\Displays\Header\User;

use app\Table\Images\Images\ImagesUsers\displayUsersImages;
use app\App;

class displayUmUser
{
    private $myself;
    private $UserImages;
    private $langFile;

    public function __construct($myself)
    {
        $this->myself =     $myself;
        //images
        $this->UserImages = new displayUsersImages($myself);
        //lang
        $this->langFile =   App::getLangModel()->getHeaderLangFile();
    }

    public function showUserPart()
    {
        return '<li class="user-menu-item" id="user-item-manageprofil" tabindex="1">
                    <a role="button">
                        <div class="pseudo"></div>
                    </a>
                    <div class="under-menu uder-menu-user">
                        <div class="under-menu-item-container col-md-12">
                            <div class="under-menu-item col-md-12">
                                <div class="user-infos-container col-md-12">
                                    <div class="um-pic-container col-md-3">
                                        <div class="pic um-pic">
                                            <a id="item-profile" href="index.php?p=profile">'. $this->UserImages->showProfileUserPic_little() .'</a>
                                        </div>
                                    </div>
                                    <div class="users-ids col-md-9">
                                        <h4>'. $this->myself->nickname .'</h4>
                                        <h5>'. $this->myself->firstname .' '. $this->myself->lastname .'</h5>
                                    </div>
                                </div>
                            </div>
                            <div class="under-menu-item options-item col-md-12">
                                <a role="button"  href="index.php?p=manageaccount">
                                    <div class="um-item-left-part col-md-3">
                                        <span class="pseudo ps-seting"></span>
                                    </div>
                                    <div class="um-item-right-part col-md-9">
                                        <h4>'. $this->langFile->title_um_user_setings .'</h4>
                                    </div>
                                </a>
                            </div>
                            <div class="under-menu-item options-item col-md-12">
                                <a role="button" href="inc/Auth/logout.php">
                                    <div class="um-item-left-part col-md-3">
                                        <span class="pseudo ps-signout"></span>
                                    </div>
                                    <div class="um-item-right-part col-md-9">
                                        <h4>'. $this->langFile->title_um_user_signout .'</h4>
                                    </div>
                                </a>
                            </div>
                        </div>
                    </div>
                 </li>';
    }

    public function show()
    {
        return $this->showUserPart();
    }
}