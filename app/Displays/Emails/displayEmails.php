<?php

namespace app\Displays\Emails;

use app\Displays\Emails\ConfirmationEmail\displayConfirmationEmail;
use app\Displays\Emails\ContactEmail\displayContactEmail;
use app\Displays\Emails\InvitationEmail\displayInvitationEmail;
use app\Displays\Emails\NotifsEmails\displayAskContactNotifEmail;
use app\Displays\Emails\NotifsEmails\displayCommentNotifEmail;
use app\Displays\Emails\NotifsEmails\displayContactNotifEmail;
use app\Displays\Emails\NotifsEmails\displayFollowNotifEmail;
use app\Displays\Emails\NotifsEmails\displayLikeNotifEmail;
use app\Displays\Emails\ReportAbuseEmail\displayReportAbuseEmail;
use app\Displays\Emails\ResetEmail\displayResetEmail;
use app\Displays\Emails\ParrainnageConfirmEmail\displayConfirmationOnParrainageEmail;
use app\Table\AppDisplay;
use core\Notifications\NotificationsManager;

class displayEmails extends AppDisplay
{
    private $todisplay;
    private $token;
    private $tokenParrain;
    private $useridToConfirm;
    private $object;
    private $message;

    //NOTIFS
    private $elemid;
    private $userIdFromNotif;
    private $userIdToNotif;
    private $notifLink;
    private $codeNotif;

    public function __construct($todisplay, $tokenParrainage = false, $token = false, $userid = false, $object = false, $message = false, $codeNotif = false, $userIdFromNotif = false, $userIdToNotif = false, $elemid = false) //#todo pas beau: token confirm a passer autrement via a tableau associatif !
    {
        parent::__construct();
        $this->todisplay =          $todisplay;
        $this->token =              $token;
        $this->tokenParrain =       $tokenParrainage;
        $this->useridToConfirm =    $userid;

        $this->object =             $object;
        $this->message =            $message;

        //concerning notifs
        if($todisplay == 'NotifEmail')
        {
            $this->elemid =             $elemid;
            $this->userIdFromNotif =    $userIdFromNotif;
            $this->userIdToNotif =      $userIdToNotif;
            $this->codeNotif =          $codeNotif;
            $notifManager =             new NotificationsManager();
            $this->notifLink =          $notifManager->generateLinkNotif($this->codeNotif, $this->elemid, $this->userIdFromNotif);

            //get codes notifs signification
            $likeNotif =        8;
            $commentNotif =     9;
            $contactNotif =     11;
            $askContactNotif =  12;
            $followerNotif =    10;

            //redef todisplay function of $codeNotif
            switch($this->codeNotif){
                case $likeNotif:
                    $this->todisplay = 'likeNotif';
                    break;
                case $commentNotif:
                    $this->todisplay = 'cmmentNotif';
                    break;
                case $contactNotif:
                    $this->todisplay = 'contactNotif';
                    break;
                case $askContactNotif:
                    $this->todisplay = 'askContactNotif';
                    break;
                case $followerNotif:
                    $this->todisplay = 'followerNotif';
                    break;
            }
        }
    }

    public function showBody()
    {
        $body ='';
        switch($this->todisplay){
            case 'InvitationEmail':
                $display = new displayInvitationEmail();
                $body = $display->show();
                break;
            case 'ConfirmEmail':
                $display = new displayConfirmationEmail($this->token, $this->useridToConfirm);
                $body = $display->show();
                break;
            case 'ReportAbuseEmail':
                $display = new displayReportAbuseEmail($this->object, $this->message);
                $body = $display->show();
                break;
            case 'ContactEmail':
                $display = new displayContactEmail($this->object, $this->message);
                $body = $display->show();
                break;
            case 'ResetEmail':
                $display = new displayResetEmail($this->token, $this->useridToConfirm);
                $body = $display->show();
                break;
            case 'ConfirmOnParrainageEmail':
                $display = new displayConfirmationOnParrainageEmail($this->token, $this->useridToConfirm, $this->tokenParrain);
                $body = $display->show();
                break;
            case 'likeNotif':
                $display = new displayLikeNotifEmail($this->userIdFromNotif, $this->userIdToNotif, $this->notifLink);
                $body = $display->show();
                break;
            case 'cmmentNotif':
                $display = new displayCommentNotifEmail($this->userIdFromNotif, $this->userIdToNotif, $this->notifLink);
                $body = $display->show();
                break;
            case 'contactNotif':
                $display = new displayContactNotifEmail($this->userIdFromNotif, $this->userIdToNotif, $this->notifLink);
                $body = $display->show();
                break;
            case 'askContactNotif':
                $display = new displayAskContactNotifEmail($this->userIdFromNotif, $this->userIdToNotif, $this->notifLink);
                $body = $display->show();
                break;
            case 'followerNotif':
                $display = new displayFollowNotifEmail($this->userIdFromNotif, $this->userIdToNotif, $this->notifLink);
                $body = $display->show();
                break;
        }
        return $body;
    }
    
    public function show()
    {
        return $this->showEmailHtmlContentUpdated($this->showBody());
    }

}