<?php


namespace app\Table\Images\Images\ImagesUsers;

use app\Table\UserModel\UserModel;
use core\Session\Session;

class displayUsersImages
{
    private $user;

    public function __construct($user = false, $iduser = false){
        $this->user = $user ? $user : Session::getInstance()->read('auth');
        if($iduser)
        {
            $model = new UserModel();
            $this->user = $model->getUserFromId($iduser);
        }
    }

    public function showProfileUserPic_little()
    {
        if($this->user->background_profile)
        {
            return '<img src="inc/img/imguser.php?sl='. $this->user->slug .'">';
        }
        else{
            return '<img src="public/img/default/defaultprofile.jpg" alt="WorldEsport default profile pic">';
        }
    }

    public function show()
    {
        //return $this->showBody();
    }
}