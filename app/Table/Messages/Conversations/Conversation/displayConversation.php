<?php

namespace app\Table\Messages\Conversations\Conversation;

use app\Table\appDates;
use app\Table\btmodifs\btmodifsDisplay;
use app\Table\Images\Images\ImagesUsers\displayUsersImages;
use app\Table\Messages\Conversations\ConversationModel;
use app\Table\Messages\Messages\MessagesModel;
use app\Table\Messages\Conversations\displayConversations;
use app\Table\Messages\Messages\Message\displayMessage;
use app\Table\UserModel\displayUser;
use app\Table\UserModel\UserModel;

class displayConversation extends displayConversations
{
    private $id;
    private $title;
    private $nbParticipants;
    private $userCreatorId;
    private $state;
    private $activated;
    private $typeConv;

    private $generateTitle;

    protected $notifInfos; //contient un tableau de var nescessaire a l'affiche des notifs convs

    protected $apercuInfos; //contient un tableau de var nescessaire a l'affichage de l'apercu conversation ds le message center

    private $messages;

    private $completeName; //utilisé ds un cas particulier

    private $maxLengthTitle; // = 23 par default
    
    public function __construct($conversation = false, $todisplay = false, $generateTitle = true)
    {
        parent::__construct(false, $todisplay);

        $this->id =                 $conversation->pk_idconversation;
        $this->title =              empty($conversation->title) ? NULL : $conversation->title;
        $this->nbParticipants =     $conversation->nbParticipants;
        $this->userCreatorId =      $conversation->fk_iduser;
        $this->state =              empty($conversation->state) ? NULL : $conversation->state;
        $this->activated =          $conversation->activated;
        $this->typeConv =           $conversation->typeConv;

        $this->generateTitle =      $generateTitle;
        $this->maxLengthTitle =     23;
        
        //si la conversation est une conversation notif, get les infos de la notif
        //si getNotif est set alors on genère la notif
        if($this->todisplay == "UmConversations")
        {
            $model = new MessagesModel();
            $this->notifInfos = $model->getNotifsInfosFromIdConv($this->id);
        }

        //si la conversation est un apercu conv ds la left bar du message center
        if($this->todisplay == "apercuDiscutions")
        {
            $this->maxLengthTitle = 27;
        }

        //define text state
        switch($this->state){
            case 0:
                $this->state = 'closed';
                break;
            case 1:
                $this->state = 'open';
                break;
            case 2:
                $this->state = 'minimized';
                break;
            case 3:
                $this->state = 'grouped';
                break;
        }

        //on ajoute ce test afin de definir si on doit generer le titre de la conversation en meme temps que l'affichage
        //car ds certains cas
        if($generateTitle)
        {
            if(!$this->title)
            {
                //si la conversation n'ets pas une nouvelle conv <=> conversation vide
                if($this->typeConv == 'userTouser')
                {
                    //get link qui sert de title au conversation user to user, c'est a dire les conversation avec un titre null
                    $model = new ConversationModel();
                    $this->title = $model->getTitleForUsertoUserConv($this->id);

                    //get complete name from link
                    $pieces = explode('>', $this->title);
                    $pieces = explode('<', $pieces[1]);
                    $this->completeName = $pieces[0];
                }
                //si la conversation est une conv de groupe
                if($this->typeConv == 'groupConv')
                {
                    $model = new ConversationModel();
                    $this->title = $model->getTitleForGroupedConv($this->id, []);
                }
                if($this->typeConv == 'emptyConv')
                {
                    $this->title = $this->langFileHeader->title_new_chat;
                }
            }
            else{
                if($this->todisplay != 'discussion')
                {
                    if(strlen($this->title) > $this->maxLengthTitle)
                    {
                        $this->title = substr($this->title, 0, $this->maxLengthTitle) . '...';
                    }
                }
            }
        }
    }

    public function getTitleConv()
    {
        return $this->title;
    }

    public function showHeader()
    {
        //get pic conv
        if($this->generateTitle)
        {
            $picContent = $this->getPicUsers();
        }
        else{
            $picContent = '';
        }

        //get bt modifs
        if($this->typeConv == 'emptyConv')
        {
            $status = 'edit-emptyConv';
        }
        if($this->typeConv == 'userTouser')
        {
            $status = 'edit-discussion-usertouser';
        }
        else if($this->typeConv == 'groupConv')
        {
            $status = 'edit-discussion-groupConv';
        }

        $display = new btmodifsDisplay($status);
        $btmodifs = $display->show();

        return '<div class="header-part">
                    <div class="conv-pic-container">
                        <div class="conv-pic-content">
                            '. $picContent .'
                        </div>                       
                    </div>
                    <div class="title-conv-container">
                        <p data-elem="conv-title">'. $this->title .'</p>
                    </div>
                    <div class="conv-edit-container" tabindex="15" data-elem="bt-container">
                        '. $btmodifs .'
                    </div>
                    <div class="state-user-container">
                        <div class="state-user-content">
                            <div class="block-status online">
                                <!--<div class="left-part">

                                </div>
                                <div class="right-part">
                                    <p>ONLINE</p>
                                </div>-->
                            </div>
                        </div>
                    </div>
                </div>';
    }

    public function showBody()
    {
        $classEmptyConv = 'active';

        $addUserContent = '<div class="input-add-container">
                                <div class="add-user-input-container input-container">
                                    <div class="editable-content" spellcheck="false" placeholder="'. $this->langFile[$this->pageName]->placeholder_adduser .'" data-elem="searh-user" contenteditable="true"></div>
                                    <div class="user-added-container" data-elem="user-added-container">
                                    </div>
                                </div>
                                <div class="add-user-results">
                                    <!-- ADD USERS RESULTS EMPLACEMENT -->
                                </div>                           
                            </div>
                            <div class="bt-container-add-user">
                                <button class="share-button bt" data-action="valid-add-user">OK</button>
                            </div>';

        $writeBoxe = '<div class="write-part">
                            <div class="write-box-container">
                                  <div class="content-editable-container">
                                      <div class="editable-content" contenteditable="true" spellcheck="false" placeholder="'. $this->langFile[$this->pageName]->placeholder_writebox .'" data-elem="write-box-chatbox"></div>
                                  </div>                              
                            </div>
                            <div class="bt-container mobile">
                                <div class="bt-content">
                                    <button class="share-button bt" data-action="sendMess">
                                        SEND
                                    </button>
                                </div>
                            </div>  
                        </div>';

        $renameConvBloc = '<div class="nameconv-container">
                                <div class="input-name-container">
                                    <div class="name-conv-input-container input-container">
                                        <div class="editable-content" spellcheck="false" placeholder="Entrez un titre de conv ..." data-elem="rename-conv-input" contenteditable="true"></div>                               
                                    </div>                                                    
                                </div>
                                <div class="bt-container-name-conv">
                                    <button class="share-button bt" data-action="valid-name-conv">OK</button>
                                </div>                       
                            </div>';

        $deleteUserContainer = '<div class="user-delete-container">
                                    <!-- DELETE USERS RESULTS EMPLACEMENT -->
                                </div>';


        if($this->typeConv == 'emptyConv')
        {
            $writeBoxe =    '';
            $deleteUserContainer = '';
            $renameConvBloc = '';
        }
        else if($this->typeConv == 'userTouser')
        {
            $classEmptyConv = '';
            $deleteUserContainer = '';
            $renameConvBloc = '';
        }
        else if($this->typeConv == 'groupConv')
        {
            $classEmptyConv = '';
        }

        $messagesContent = '';
        return '<div class="body-part">
                    <div class="add-user-container '.$classEmptyConv.'">
                        '. $addUserContent .'               
                    </div>
                    '. $renameConvBloc .'
                    '. $deleteUserContainer .'
                    <div class="message-part" data-elem="messages-container">                      
                        '. $messagesContent .'
                    </div>
                    '. $writeBoxe .'
                </div>';
    }

    public function showDiscussion()
    {
        if($this->typeConv == 'emptyConv')
        {
            $emptyConv = 'true';
        }
        else{
            $emptyConv = 'false';
        }
        return '<div class="discussion-content" data-elem="discussion-content" data-conv="'. $this->id .'" empty-conv="'. $emptyConv .'">
                    '. $this->showHeader() .
                       $this->showBody() .'
                </div>';
    }

    public function showHeaderChatBoxe()
    {
        return '<div class="header-chat-box '. $this->typeConv .'">
                    <div class="online-dot-container">
                        <p></p>
                    </div>
                    <div class="chat-box-title-container">
                          <div class="chat-box-title">
                               <p data-elem="conv-title">'. $this->title.'<span class="mask-link"></span></p>
                          </div>
                     </div>
                    <div class="chat-box-bt-container">
                        <div class="chat-bt ico-container" data-elem="conv-options">
                            <div class="ico-gear" data-action="chatBox-options">
    
                            </div>
                            <div class="edit-options">
                               
                            </div>
                        </div>
                        <div class="chat-bt" data-action="close-chatbox">
                            <p>X</p>
                        </div>
                        <div class="chat-bt" data-action="minimize-box">
                            <p>--</p>
                        </div>
                        <div class="chat-bt" data-action="add-user-to-convers">
                            <p>+</p>
                        </div>
                    </div>
                    <div class="nbnotif-container">
                        <div class="nb-notifs">
                           <p data-elem="notif-cntr"></p>
                        </div>
                    </div>
                </div>';
    }

    public function showBodyChatBox()
    {
        //la conv est une nouvelle conv vide (aucun user dedans exept le currentUser)
        if($this->typeConv == 'emptyConv')
        {
            $messageBox =   '';

            $writeBoxe =    '';

            $classEmptyConv = 'active';
        }
        else{
            $messages =         '';
            $classEmptyConv =   '';
            if($this->messages)
            {
                //$displayMessages = new displayMessages($this->messages, $this->todisplay);
                //$messages = $displayMessages->show();
            }

            $messageBox =  '<div class="messages-box" data-elem="messages-box">'. $messages .'</div>';

            $writeBoxe = '<div class="write-box-container">
                              <div class="content-editable-container">
                                  <div class="editable-content" contenteditable="true" spellcheck="false" placeholder="'. $this->langFile[$this->pageName]->placeholder_writebox .'" data-elem="write-box-chatbox"></div>
                              </div>                              
                          </div>';
        }


        return '<div class="body-chat-box">
                    <div class="add-user-container '.$classEmptyConv.'">
                        <div class="input-add-container">
                            <div class="add-user-input-container">
                                <div class="editable-content" spellcheck="false" placeholder="'. $this->langFile[$this->pageName]->placeholder_adduser .'" data-elem="searh-user" contenteditable="true"></div>
                                <div class="user-added-container" data-elem="user-added-container">
                                </div>
                            </div>
                            <div class="add-user-results">
                                <!-- ADD USERS RESULTS EMPLACEMENT -->
                            </div>                           
                        </div>
                        <div class="bt-container-add-user">
                            <button class="share-button bt" data-action="valid-add-user">OK</button>
                        </div>                       
                    </div>
                    <div class="nameconv-container">
                        <div class="input-name-container">
                            <div class="name-conv-input-container">
                                <div class="editable-content" spellcheck="false" placeholder="Entrez un titre de conv ..." data-elem="rename-conv-input" contenteditable="true"></div>                               
                            </div>                                                    
                        </div>
                        <div class="bt-container-name-conv">
                            <button class="share-button bt" data-action="valid-name-conv">OK</button>
                        </div>                       
                    </div>
                    <div class="user-delete-container">
                        <!-- DELETE USERS RESULTS EMPLACEMENT -->
                    </div>
                    '. $messageBox .'
                    '. $writeBoxe .'
                </div>';
    }

    public function showChatBoxe()
    {
        //la conv est une empty conv
        if($this->typeConv == 'emptyConv')
        {
            return '<div class="chat-box" id="chatBox-'. $this->id .'" data-state="'. $this->state .'" empty-conv="true">
                        '. $this->showHeaderChatBoxe() .'
                        '. $this->showBodyChatBox() .'
                    </div>';
        }
        //la conv est une conv normale (group, usertouser)
        else{
            
        }
        return '<div class="chat-box" id="chatBox-'. $this->id .'" data-state="'. $this->state .'">
                    '. $this->showHeaderChatBoxe() .'
                    '. $this->showBodyChatBox() .'
                </div>';
    }

    public function showChatNotif()
    {
        if(!empty($this->notifInfos))
        {
            $lastMessage =  $this->notifInfos['lastMessage'];
            $contentMess =  $lastMessage->texte;
            $dateMess =     $lastMessage->date;
            $appDate =      new appDates($dateMess);
            $timeMess =     $appDate->getDate();
            $idAuth =       $lastMessage->fk_iduser;
            $nbNotifs =     $this->notifInfos['nbNotifs'];
            //generate title
            $title = $this->completeName;
            if($this->typeConv == 'groupConv')
            {
                $title = $this->title;
            }

            //get author of message variables
            $picUser = $this->getPicUsers();

            return '<div class="discussion col-md-12" data-checked="false" data-elem="conv-notified" data-chatNotif="'. $this->id .'">
                        <div class="left-part">
                           '. $picUser .'                               
                        </div>
                        <div class="right-part">
                            <div class="user-sender">
                                <p data-elem="title-chat">'. $title .'</p>
                            </div>
                            <div class="user-message">
                                <p data-elem="message">'. $contentMess .'</p>
                            </div>
                            <div class="nbnotif-container">
                                <div class="nb-notifs">
                                    <p data-elem="notif-cntr">'. $nbNotifs .'</p>
                                </div>                              
                            </div>
                            <!--<div class="user-status online">
                                <p>ONLINE</p>
                            </div>-->
                            <div class="user-last-message-time">
                                <p data-elem="date-lst-mess" data-date="'. $dateMess .'">'. $timeMess .'</p>
                            </div>
                        </div>
                    </div>';
        }
    }

    public function showApercuDiscution()
    {
        $model = new MessagesModel();
        $apercuInfos = $model->getApercuConvArray($this->id);
        $lastMessage =  $apercuInfos['lastMessage'];
        if(empty($lastMessage))
        {
            $contentMess = '';
            $dateMess = '';
            $timeMess = '';
            $nbNotifs = '';
        }
        else {
            $contentMess = $lastMessage->texte;
            $dateMess = $lastMessage->date;
            $appDate = new appDates($dateMess);
            $timeMess = $appDate->getDate();
            $nbNotifs = $apercuInfos['nbNotifs'];
        }

        //generate title
        $title = $this->completeName;
        if($this->typeConv == 'groupConv')
        {
            $title = $this->title;
        }
        else if($this->typeConv == 'emptyConv')
        {
            $title = $this->langFileHeader->bt_new_tchat;
        }

        //get pic conversation
        $picContent = $this->getPicUsers();

        //display checked cross or not
        if($nbNotifs == '')
        {
            if($this->typeConv == 'emptyConv')
            {
                $notifs = '';
            }
            else{
                if($this->activated)
                {
                    $notifs =  '<div class="nb-notifs" data-checked="true">
                                     <p data-elem="notif-cntr"></p>
                                </div>';
                }
                else{
                    $notifs =  '<div class="nb-notifs">
                                     <p data-elem="notif-cntr"></p>
                                </div>';
                }

            }

        }
        else{
            $notifs = '<div class="nb-notifs">
                             <p data-elem="notif-cntr">'. $nbNotifs .'</p>
                        </div>';
        }

        return '<div class="discussion col-md-12" data-elem="ap-discussion" data-conv="'. $this->id .'" data-checked="false">
                    <div class="discussion-content slidedLeft">
                        <div class="left-part">
                            <div class="left-part-content">
                                '. $picContent .'
                            </div>                           
                        </div>
                        <div class="right-part">
                            <div class="user-sender">
                                <p data-elem="title-chat">'. $title .'</p>
                            </div>
                            <div class="user-message">
                                <p data-elem="message">'. $contentMess .'</p>
                            </div>
                            <div class="nbnotif-container">
                                '. $notifs .'                              
                            </div>
                            <!--<div class="user-status online">
                                <p>ONLINE</p>
                            </div>-->
                            <div class="user-last-message-time">
                                <p data-elem="date-lst-mess" data-date="'. $dateMess .'">'. $timeMess .'</p>
                            </div>
                        </div>
                    </div>  
                </div>';
    }

    public function getPicUsers()
    {
        //get id participants to display pic users
        $model = new ConversationModel();
        $idusers = $model->getIdParticipants($this->id, true);
        $nbUsers = sizeof($idusers);
        $picContent = '';
        switch($this->typeConv){
            //cas userTouser, display 1 pic
            case 'userTouser':
                $display = new displayUsersImages(false, $idusers[0]);
                $picUsers = $display->showProfileUserPic_little();
                $picContent = '<div class="pic-discussion pic">
                                        <div class="pic-container" data-elem="u-pic">
                                            '. $picUsers .'
                                        </div>
                                    </div>';
                break;

            //case groupConv, display 1 pic / 2 pic or 3 pics
            case 'groupConv':
                if($nbUsers == 1)
                {
                    $display = new displayUsersImages(false, $idusers[0]);
                    $picUsers = $display->showProfileUserPic_little();
                    $picContent = '<div class="pic-discussion pic">
                                        <div class="pic-container" data-elem="u-pic">
                                            '. $picUsers .'
                                        </div>
                                   </div>';
                }
                else if($nbUsers == 2)
                {
                    $picUsers = '';
                    foreach($idusers as $iduser)
                    {
                        $display = new displayUsersImages(false, $iduser);
                        $picUsers .= '<div class="pic-container" data-elem="u-pic">
                                          '. $display->showProfileUserPic_little() .'
                                      </div>';
                    }
                    $picContent = '<div class="pic-discussion pic twopic">
                                       '. $picUsers .'
                                   </div>';
                }
                else if($nbUsers > 2)
                {
                    $picUsers = '';
                    foreach($idusers as $key=>$iduser)
                    {
                        if($key < 3)
                        {
                            $display = new displayUsersImages(false, $iduser);
                            $picUsers .= '<div class="pic-container" data-elem="u-pic">
                                               '. $display->showProfileUserPic_little() .'
                                          </div>';
                        }
                    }
                    $picContent = '<div class="pic-discussion pic threepic">
                                       '. $picUsers .'
                                   </div>';
                }
                break;

            //case empty conv: display default pic
            case 'emptyConv':
                $picUser = '<img src="public/img/default/defaultprofile.jpg" alt="WorldEsport default profile pic">';
                $picContent = '<div class="pic-discussion pic">
                                    <div class="pic-container" data-elem="u-pic">
                                          '. $picUser .'
                                    </div>                                  
                               </div>';
                break;
        }

        return $picContent;
    }

    /*public function showBody()
    {
        //define the todisplay message
        $todisplay = '';
        if($this->todisplay == "apercu-conv-message-center" || $this->todisplay == "conv-message-center")
        {
            $todisplay = "message-center";
        }

        //get message html
        $content = '';
        foreach($this->messages as $message)
        {
            $display = new displayMessage($message, $todisplay, false, true);
            $content .= $display->show();
        }

        return $content;
    }*/

    function show()
    {
        if($this->todisplay == "chatBox")
        {
            return $this->showChatBoxe();
        }
        if($this->todisplay == "UmConversations")
        {
            return $this->showChatNotif();
        }
        if($this->todisplay == "apercuDiscutions")
        {
            return $this->showApercuDiscution();
        }
        if($this->todisplay == "discussion")
        {
            return $this->showDiscussion();
        }
    }
}