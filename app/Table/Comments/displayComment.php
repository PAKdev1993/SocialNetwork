<?php

namespace app\Table\Comments;

use app\App;
use app\Table\appDates;
use app\Table\AppDisplay;
use core\Session\Session;

use app\Table\UserModel\UserModel;
use app\Table\UserModel\displayUser;

class displayComment extends AppDisplay
{
    private $id;
    private $text;
    private $idpost;
    private $date;
    private $contentCensured;

    private $user_profile_pic;

    private $data_elem;
    
    private $user;

    private $langfile;

    public function __construct($comment, $langfile = false)
    {
        parent::__construct();
        $this->id = $comment->pk_idcomment;
        $this->text = $comment->texte;
        $this->idpost = $comment->fk_idpost;
        $this->contentCensured = $comment->ContentCensured;
        $this->langfile = $langfile;

        //transformer la date
        $appDate = new appDates($comment->date);
        $this->date = $appDate->getDate();

        //get user from id
        $iduser = $comment->fk_iduser;
        //#todo remove db parameters from here
        $userModel = new UserModel(App::getDatabase());
        $this->user = $userModel->getUserFromId($iduser); //#todo eviter de faire cette requette et recuperer les paramètres user d'une autre manière (Si possible)

        //profile pic of user
        $displayUser = new displayUser($this->user);
        $this->user_profile_pic = $displayUser->showUserProfilePic();

        if($this->user->pk_iduser == Session::getInstance()->read('auth')->pk_iduser)
        {
            $this->data_elem = '{"wecid":"'. $this->id .'"}';
        }
        else{
            $this->data_elem = '{"wecid":"'. $this->id .'","weid":"'. $this->user->pk_iduser .'"}';
        }
    }

    public function show()
    {
        return "
            <div class='post-comments col-md-12'>
                <div class='comment-container col-md-12'>
                    <div class='comment-user-pic-container col-md-2'>
                        <div class='comment-user-pic pic'>
                            <a href='index.php?p=profile&u=". $this->user->slug ."'>$this->user_profile_pic</a>
                        </div>
                    </div>
                    <div class='comment col-md-10' data-elems='". $this->data_elem ."'>
                        <div class='comment-text-container'>                            
                            <div class='input comment-input'>". $this->text ."</div>
                            <div class='comment-date'><p class='post-user-name'>". $this->user->nickname ."</p>". $this->date ."</div>
                        </div>
                    </div>
                </div>
            </div> 
        ";
    }
}