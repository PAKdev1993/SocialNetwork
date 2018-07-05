<?php
namespace app\Displays\SearchMessagerie\displayConvResult;

use app\Table\Images\Images\ImagesUsers\displayUsersImages;
use app\Table\Messages\Conversations\ConversationModel;

class displayConvResult
{
    public function __construct($result, $todisplay)
    {
        $this->id =                 $result->fk_idconversation;

        $model = new ConversationModel();
        $this->typeConv = $model->getTypeConv($this->id);
        
        //get title conv
        //si la conversation n'ets pas une nouvelle conv <=> conversation vide
        if($this->typeConv == 'userTouser')
        {
            //get link qui sert de title au conversation user to user, c'est a dire les conversation avec un titre null
            $this->title = $model->getTitleForUsertoUserConv($this->id);

            //get complete name from link
            $pieces = explode('>', $this->title);
            $pieces = explode('<', $pieces[1]);
            $this->completeName = $pieces[0];
        }
        //si la conversation est une conv de groupe
        if($this->typeConv == 'groupConv')
        {
            $this->title = $model->getTitleForGroupedConv($this->id, array());
        }

        $this->todisplay = $todisplay;
    }

    public function generatePicConv()
    {
        //generate pic converation
        $model =        new ConversationModel();
        $idusers =      $model->getIdParticipants($this->id, true);
        $nbUsers =      sizeof($idusers);
        $picContent =   '';
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
                                   '. $picUser .'
                               </div>';
                break;
        }

        return $picContent;
    }

    public function showFoundConv()
    {
        if($this->typeConv != 'emptyConv')
        {
            $picContent = $this->generatePicConv();

            return '<div class="discussion col-md-12" data-elem="ap-discussion" data-conv="'. $this->id .'">
                        <div class="left-part">
                            <div class="left-part-content">
                                '. $picContent .'
                            </div>
                        </div>
                        <div class="right-part">
                            <div class="user-sender">
                                <p data-elem="title-chat">'. $this->title .'</p>
                            </div>                      
                            <!--<div class="user-status online">
                                <p>ONLINE</p>
                            </div>-->
                        </div>
                    </div>';
        }
    }

    public function show()
    {
        if($this->todisplay == 'convs')
        {
            return $this->showFoundConv();
        }
    }
}