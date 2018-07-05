<?php

namespace app\Table\Messages\Messages\Message;

use app\Table\appDates;
use app\Table\AppDisplay;
use app\Table\Images\Images\ImagesUsers\displayUsersImages;
use app\Table\Messages\Messages\displayMessages;
use app\Table\Messages\Messages\MessagesModel;
use app\Table\UserModel\UserModel;
use core\Functions;

class displayMessage extends displayMessages
{
    private $id;
    private $idConvers;
    private $iduser;
    private $texte;
    private $date;
    private $dateToDisplay;
    private $getReaded;

    private $userImage;

    public function __construct($message = false, $todisplay = false, $getReaded = false)
    {
        parent::__construct();
        $this->id =         $message->pk_idmessage ? $message->pk_idmessage : '';
        $this->idConvers =  $message->fk_idconversation;
        $this->iduser =     $message->fk_iduser;
        $this->texte =      $message->texte;
        $this->date =       $message->date;

        //define nickname user
        $this->nicknameUser = Functions::getUserNickname($this->iduser);

        $this->getReaded = $getReaded;

        //get date
        $appDates = new appDates($this->date, "dateSQL", 'messageChat');
        $this->dateToDisplay = $appDates->getDate();

        if($this->iduser != $this->session->read('auth')->pk_iduser)
        {
            $displayUserImages = new displayUsersImages(false, $this->iduser);
            $this->userImage = $displayUserImages->showProfileUserPic_little();
        }
    }

    public function showMessage()
    {
        $readByString   = '';
        $textreadeBy    = '';
        if($this->getReaded)
        {
            $model = new MessagesModel();
            //get user string
            $readByString = $model->getReadedUserIds($this->idConvers, $this->date, $this->iduser);
            if($readByString != '')
            {
                //get user nicknames
                $idusersArr = explode(',',$readByString);
                $arrTmp = Functions::getUsersNicknameInArray($idusersArr);
                if(!empty($arrTmp))
                {
                    $nicknameString = implode(', ',$arrTmp);
                    $textreadeBy = $this->langFile[$this->pageName]->readedBy .' '. $nicknameString;
                }
            }
        }
        return '<div class="message-user-container" data-elem="message" data-u="'. $this->iduser .'">
                    <div class="message-content">
                        <div class="user-infos-container pic" data-elem="u-pic">
                            '. $this->userImage .'
                        </div>
                        <div class="user-nick-container">
                            <div class="user-nick-content">
                                <p data-elem="nick-mess">'.  $this->nicknameUser .'</p>
                            </div>
                        </div>
                        <div class="user-message" data-elem="chat-usr-mess" data-date="'. $this->date .'">
                            '. $this->texte .'
                        </div>
                        <div class="date-container">
                            <div class="date-content">
                                 <p data-elem="date-mess">'. $this->dateToDisplay .'</p>
                            </div>                               
                        </div>
                        <div class="reader-container">
                            <div class="reader-content">
                                <p data-elem="readed" readBy="'. $readByString .'">'.$textreadeBy.'</p>
                            </div>
                        </div>
                    </div>    
                </div>';
    }

    public function showMyMessage()
    {
        $readByString   = '';
        $textreadeBy    = '';
        if($this->getReaded)
        {
            $model = new MessagesModel();
            //get user string
            $readByString = $model->getReadedUserIds($this->idConvers, $this->date, $this->iduser);
            if($readByString != '')
            {
                //get user nicknames
                $idusersArr = explode(',',$readByString);
                $arrTmp = Functions::getUsersNicknameInArray($idusersArr);
                if(!empty($arrTmp))
                {
                    $nicknameString = implode(', ',$arrTmp);
                    $textreadeBy = $this->langFile[$this->pageName]->readedBy.' '. $nicknameString;
                }
            }
        }

        return '<div class="my-message-container" data-elem="message" data-u="'. $this->iduser .'">
                    <div class="message-content">
                        <div class="user-message" data-elem="chat-usr-mess" data-date="'. $this->date .'">
                            '. $this->texte .'
                        </div>                  
                        <div class="date-container">
                            <div class="date-content">
                                 <p data-elem="date-mess">'. $this->dateToDisplay .'</p>
                            </div>                               
                        </div>
                        <div class="reader-container">
                            <div class="reader-content">
                                <p data-elem="readed" readBy="'. $readByString .'">'.$textreadeBy.'</p>
                            </div>
                        </div>
                    </div>       
                </div>';
    }
    
    public function show()
    {
        if($this->iduser == $this->session->read('auth')->pk_iduser)
        {
            return $this->showMyMessage();
        }
        else{
            return $this->showMessage();
        }
    }
}