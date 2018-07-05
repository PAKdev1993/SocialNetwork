<?php

namespace core\Notifications;

use app\Table\ManageAccount\ManageAccountModel;
use app\Table\Notifications\displayNotifications;
use app\Table\Posts\PostModel;
use app\Table\ProfileViewers\ProfileViewModel;
use app\Table\Notifications\NotificationModel;
use app\Table\UserModel\UserModel;
use core\Email\EmailManager;
use core\Session\Session;

class NotificationsManager
{
    private $NotificationsModel;
    private $ProfileViewModel;

    private $currentUser;

    public function __construct()
    {
        $this->currentUser = Session::getInstance()->read('auth');
        $this->ProfileViewModel =   new ProfileViewModel();
        $this->NotificationsModel = new NotificationModel();
    }

    public function getMyNotificationsForUnderMenu()
    {
        $notifications = $this->NotificationsModel->getMyNotificationsUnderMenu();
        $display = new displayNotifications($notifications, 'NotificationsPage');
        return $display->show();
    }

    public function getMyNotifications()
    {
        $notifications = $this->NotificationsModel->getAllMyNotifications();
        $display = new displayNotifications($notifications, 'NotificationsPage');
        return $display->show();
    }
    
    public function getNbNotifsForProfileViewers()
    {
        $nbNewNotifs = $this->ProfileViewModel->getNbProfileViewUnconsulted();
        return $nbNewNotifs;
    }

    public function resetNotifsForProfileViewers()
    {
        $this->ProfileViewModel->consultMyProfileVewer();
    }

    public function getNbNotifications()
    {
        return $this->NotificationsModel->getNbMyNotifications();
    }

    //$to est a false car il est deduit du $elemid ds certain cas
    //$elemid est a false car ds certain cas il est inutile
    public function createNotification($from, $to = false, $codeNotif, $elemid = false)
    {
        //ANTISPAM
        //checker si la notif n'a pas été crée auparavant
        $model = new NotificationModel();

        //s'il n'y a pas de $to alors c'est qu'il est deduit du $elemid
        if(!$to)
        {
            $to = $this->getToFromCodeNotifAndElemid($codeNotif, $elemid);
        }
        //lorsque le to est déduit, on test s'il n'est pas egal a l'user courant: sert a eviter le cas ou un user aime et/ou commente ses propres posts
        if($to == $this->currentUser->pk_iduser)
        {
            return false;
        }
        //si l'elem n'est pas definis c'ets que la notification porte sur un user ex: someone if following you, dinon l'ememid est defini et la notif porte sur un post par exemple
        if(!$elemid)
        {
            $elemid = NULL;
        }
        //si la notif existe deja(cas du spam du boutton)
        $notifExist = $model->notifExist($from, $to, $codeNotif, $elemid);
        if($notifExist)
        {
            return false;
        }

        //save de la notification
        $this->NotificationsModel->saveNotification($from, $to, $codeNotif, $elemid);
        //Send notif email if nescessary
        $this->sendNotifEmail($from, $to, $codeNotif, $elemid);
    }

    public function sendNotifEmail($from, $to, $codeNotif, $elemid)
    {
        //get codes notifs signification
        $likeNotif =        8;
        $commentNotif =     9;
        $contactNotif =     11;
        $askContactNotif =  12;
        $followerNotif =    10;

        //get userto, notifEmail parameters
        $model = new ManageAccountModel();
        $categories = $model->getWishReceiveEmailWhenFromIdUser($to);
        $emailWhen = $categories['emailWhen'][0]; //#todo corriger ca c'est moche, le pb viens de la fonction getWish etc

        //mail or not
        $mailManager = new EmailManager();
        switch($codeNotif){
            case $likeNotif:
                if($emailWhen->email_when_user_like_post != 0) $mailManager->sendNotifEmailToUser($from, $to, $codeNotif, $elemid);
                break;
            case $commentNotif:
                if($emailWhen->email_when_user_comment_post != 0) $mailManager->sendNotifEmailToUser($from, $to, $codeNotif, $elemid);
                break;
            case $contactNotif:
                if($emailWhen->email_when_user_accept_contact_request != 0) $mailManager->sendNotifEmailToUser($from, $to, $codeNotif, $elemid);
                break;
            case $askContactNotif:
                if($emailWhen->email_when_user_wtb_part_of_network != 0) $mailManager->sendNotifEmailToUser($from, $to, $codeNotif, $elemid);
                break;
            case $followerNotif:
                if($emailWhen->email_when_user_follow_you != 0) $mailManager->sendNotifEmailToUser($from, $to, $codeNotif, $elemid);
                break;
        }
    }

    public function resetMyNotifs()
    {
        $this->NotificationsModel->consultMyNotifications();
    }

    public function getToFromCodeNotifAndElemid($codeNotif, $elemid)
    {
        $toid = '';
        switch($codeNotif) {
            //LIKE
            case 8:
                $model = new PostModel();
                $toid = $model->getPostAuthor($elemid);
                break;
            //COMMENT
            case 9:
                $model = new PostModel();
                $toid = $model->getPostAuthor($elemid);
                break;
        }
        return $toid;
    }

    public function generateLinkNotif($codeNotif, $idelem = false, $from = false)
    {
        $link = '';
        switch($codeNotif) {
            //LIKE
            case 8:
                $model = new PostModel();
                //test si le post est ds les 25 derniers de l'user
                if($model->isOldPost($idelem))
                {
                    //si le post est classé old, verifier qu'il existe toujour
                    if($model->getPostFromid($idelem))
                    {
                        $link = 'permalink.php?p=post&elem='. $idelem .'';
                    }
                    //sinon afficher la 404
                    else{
                        $link = 'index.php?p=notfound&err=0'; //#todo generer les erreurs de la not found dinamiquement
                    }
                }
                else{
                    $link = 'index.php?p=profile&gotopost='. $idelem .'#mytimeline';
                }
                break;
            //COMMENT
            case 9:
                $model = new PostModel();
                //test si le post est ds les 25 derniers de l'user
                if($model->isOldPost($idelem))
                {
                    //si le post est classé old, verifier qu'il existe toujour
                    if($model->getPostFromid($idelem))
                    {
                        $link = 'permalink.php?p=post&elem='. $idelem .'';
                    }
                    //sinon afficher la 404
                    else{
                        $link = 'index.php?p=notfound&err=0'; //#todo generer les erreurs de la not found dinamiquement
                    }
                }
                else{
                    $link = 'index.php?p=profile&gotopost='. $idelem .'#mytimeline';
                }
                break;
            //SOMEONE FOLLOWING YOU
            case 10:
                $model = new UserModel();
                $userslug = $model->getSlugFromId($from);
                $link = 'index.php?p=profile&u='. $userslug .'';
                break;
            //SOMEONE ACCEPTED YOUR CONTACT REQUEST
            case 11:
                $model = new UserModel();
                $userslug = $model->getSlugFromId($from);
                $link = 'index.php?p=profile&u='. $userslug .'';
                break;
            //SENT CONTACT REQUEST
            case 12:
                $link = 'index.php?p=mycommunity#my-pending-contacts';
                break;
        }
        return $link;
    }
}