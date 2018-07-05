<?php
namespace app\Displays\DeleteUserMessagerie;

use app\Displays\DeleteUserMessagerie\displayUserToDelete\displayUserToDelete;

class displayUsersToDelete
{
    private $todisplay;
    private $users;

    public function __construct($users = array(), $todisplay = false)
    {
        $this->users =      $users;
        $this->todisplay =  $todisplay;
    }

    public function displayChatBoxUsersToDelete()
    {
        $content = '';
        foreach($this->users as $user)
        {
            $display = new displayUserToDelete($user, $this->todisplay);
            $content .= $display->show();
        }
        return "<div class='user-delete-content'>                   
                     ". $content ."
                </div>
                <div class='bt-val-del-container'>
                    <button class='share-button bt' data-action='valid-delete-user-conv'>
                        Delete users selected
                    </button>
                </div>";
    }

    public function displayMessageCenterUsersToDelete()
    {

    }

    public function show()
    {
        if($this->todisplay == "chatBoxe")
        {
            return $this->displayChatBoxUsersToDelete();
        }
        if($this->todisplay == "messageCenterResult")
        {
            return $this->displayMessageCenterResults();
        }
    }
}