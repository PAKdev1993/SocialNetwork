<?php

namespace app\Table\Images\Images\ImagesForAlbumPreview;

use app\Table\AppDisplayElem;
use core\Session\Session;

class displayImagesForAlbumPreview extends AppDisplayElem
{
    //USER VARIABLES
    private $userToDisplay;

    public function __construct($imgObj, $userToDisplay = false)
    {
        parent::__construct();
        $this->id       = $imgObj->pk_idimage;
        $this->iduser   = $imgObj->fk_iduser;
        $this->idpost   = $imgObj->fk_idpost;
        $this->name     = $imgObj->name;
        $this->title    = $imgObj->title;
        $this->description = $imgObj->description;
        $this->index    = $imgObj->index;
        $this->date     = $imgObj->date;
        $this->slug     = $imgObj->slug;

        $this->dateMonthYear = $time = strtotime($this->date);
        $this->dateDir = date("m",$time) . '_' . date("Y",$time);

        //USER
        $this->userToDisplay = $userToDisplay ? $userToDisplay : Session::getInstance()->read('auth');
    }

    public function showBody()
    {
        return '<img src="inc/img/imgalpreview.php?imgname='. $this->slug .'&d='. $this->dateDir .'&u='. $this->userToDisplay->slug .'">';
    }

    public function show()
    {
        return $this->showBody();
    }
}