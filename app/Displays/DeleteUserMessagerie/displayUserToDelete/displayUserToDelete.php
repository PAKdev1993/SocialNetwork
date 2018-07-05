<?php
namespace app\Displays\DeleteUserMessagerie\displayUserToDelete;

use app\Table\Images\Images\ImagesUsers\displayUsersImages;
use core\Session\Session;

class displayUserToDelete
{
    public function __construct($user, $todisplay)
    {
        $this->todisplay = $todisplay;
        
        $this->id =         (empty($user->pk_iduser)) ? '' : $user->pk_iduser;
        $this->nickname =   (empty($user->nickname))  ? '' : $user->nickname;
        $this->firstname =  (empty($user->firstname)) ? '' : $user->firstname;
        $this->lastname =   (empty($user->lastname))  ? '' : $user->lastname;

        $this->myid = Session::getInstance()->read('auth')->pk_iduser;

        //images
        $this->UserImages = new displayUsersImages($user);
    }

    public function showLeftPart()
    {
        return '<div class="user-pic-container">
                    <div class="pic">
                        '. $this->UserImages->showProfileUserPic_little() .'
                    </div>
                </div>';
    }

    public function showRightPart()
    {
        return '<div class="users-ids">
                    <h4>'. $this->nickname .'</h4>
                    <h5>'. $this->firstname .'  '. $this->lastname .'</h5>
                </div>';
    }

    public function showUserToDelete()
    {
        if($this->id != $this->myid)
        {
            return "<div class='user-to-delete' data-id='". $this->id ."' data-action='del-user-from-conv'>
                    ". $this->showLeftPart() ."
                    ". $this->showRightPart()."
                    <div class='bt-deluser-container'>
                        <div class='logo-container'>
                            <p data-action='sel-usr-to-del-conv'> </p>
                        </div>                                                                      
                    </div>
                </div>";
        }
        else{
            return "<div class='user-to-delete'>
                        ". $this->showLeftPart() ."
                        ". $this->showRightPart()."                   
                    </div>";
        }

    }

    public function show()
    {
        if($this->todisplay == 'chatBoxe')
        {
            return $this->showUserToDelete();
        }
    }
}