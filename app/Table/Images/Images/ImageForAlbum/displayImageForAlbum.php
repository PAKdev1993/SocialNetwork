<?php

namespace app\Table\Images\Images\ImageForAlbum;

use app\Table\AppDisplayElem;
use core\Session\Session;

class displayImageForAlbum extends AppDisplayElem
{
    //IMAGES ATTRIBUTES
    private $id;
    private $iduser;
    private $idpost;
    private $name;
    private $title;
    private $date;
    private $slug;

    //USER VARIABLES
    private $userToDisplay;

    public function __construct($imgObj, $index, $userToDisplay = false)
    {
        parent::__construct();

        $this->id       = $imgObj->pk_idimage;
        $this->iduser   = $imgObj->fk_iduser;
        $this->idpost   = $imgObj->fk_idpost;
        $this->name     = $imgObj->name;
        $this->title    = $imgObj->title;
        $this->description = $imgObj->description;
        $this->date     = $imgObj->date;
        $this->slug     = $imgObj->slug;

        $this->index = $index;
        
        $this->dateMonthYear = $time = strtotime($this->date);
        $this->dateDir = date("m",$time) . '_' . date("Y",$time);

        //USER
        $this->userToDisplay = $userToDisplay ? $userToDisplay : Session::getInstance()->read('auth');
    }

    public function showMyImg()
    {
        return '<img src="inc/img/imgal.php?imgname='. $this->slug .'&u='. $this->currentUser->pk_iduser .'&d='. $this->dateDir .'" alt="'. $this->title .'">';
    }

    public function showUserImg()
    {
        return '<img src="inc/img/imgal.php?imgname='. $this->slug .'&u='. $this->userToDisplay->pk_iduser .'&d='. $this->dateDir .'" alt="'. $this->title .'">';
    }

    public function showMyImgBody()
    {
        return '<div class="picture-elem col-md-2">
                    <div role="button" class="bt-preview" data-sl="'. $this->currentUser->slug .'" data-weid="'. $this->currentUser->pk_iduser .'" data-elem="'. $this->index .'" data-d="'. $this->dateDir .'">
                        <div class="picture-header-container col-md-12">
                            <div class="title-pic">
                                '. $this->title .'
                            </div>
                        </div>
                        <div class="picture-body-container col-md-12">
                            <div class="picture-body">
                                '. $this->showMyImg() .'
                            </div>
                        </div>
                    </div>
                </div>';

    }

    public function showUserImgBody()
    {
        return '<div class="picture-elem col-md-2">
                    <div role="button" class="bt-preview" data-sl="'. $this->userToDisplay->slug .'" data-weid="'. $this->userToDisplay->pk_iduser .'" data-elem="'. $this->index .'" data-d="'. $this->dateDir .'">
                        <div class="picture-header-container col-md-12">
                            <div class="title-pic">
                                '. $this->title .'
                            </div>
                        </div>
                        <div class="picture-body-container col-md-12">
                            <div class="picture-body">
                                '. $this->showUserImg() .'
                            </div>
                        </div>
                    </div>
                </div>';

    }

    public function show()
    {
        if(Session::getInstance()->read('current-state')['state'] == 'owner')
        {
            return $this->showMyImgBody();
        }
        if(Session::getInstance()->read('current-state')['state'] == 'viewer')
        {
            return $this->showUserImgBody();
        }
    }
}