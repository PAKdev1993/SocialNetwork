<?php
namespace app\Displays\SearchMessagerie\displayAddUserResult;

use app\Table\Images\Images\ImagesUsers\displayUsersImages;

class displayAddUserResult
{
    public function __construct($result, $todisplay)
    {
        $this->id =         (empty($result->pk_iduser)) ? '' : $result->pk_iduser;
        $this->nickname =   (empty($result->nickname))  ? '' : $result->nickname;
        $this->firstname =  (empty($result->firstname)) ? '' : $result->firstname;
        $this->lastname =   (empty($result->lastname))  ? '' : $result->lastname;
        //images
        $this->UserImages = new displayUsersImages($result);

        $this->todisplay = $todisplay;
    }

    public function showLeftPart()
    {
        return '<div class="user-pic-container">
                    <div class="user-pic-content">
                        <div class="pic">
                            '. $this->UserImages->showProfileUserPic_little() .'
                        </div>
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

    public function showUserToAdd()
    {
        return "<div class='user-to-add' data-id='". $this->id ."' data-action='add-user-to-conv'>
                    ". $this->showLeftPart() ."
                    ". $this->showRightPart()."
                </div>";
    }

    public function show()
    {
        if($this->todisplay == 'friends')
        {
            return $this->showUserToAdd();
        }
    }
}