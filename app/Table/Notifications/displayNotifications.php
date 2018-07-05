<?php

namespace app\Table\Notifications;

use app\Table\AppDisplay;
use app\Table\Notifications\Notification\displayNotification;
use core\Notifications\NotificationsManager;
use core\Session\Session;

class displayNotifications extends AppDisplay
{
    private $notifications;
    private $todisplay; // = NotificationsPage pour afficher la page notification || = UmNotification pour afficher le sous menu du header
    private $nbNotifs;

    protected $pageName;

    public function __construct($notifications = false, $todisplay = false)
    {
        $this->pageName = 'notifications';
        parent::__construct(false, $this->pageName);
        $this->todisplay =         $todisplay;
        $this->notifications =     $notifications;

        //CALCUL NB NOTIFS
        $coreNotif =        new NotificationsManager();
        $this->nbNotifs =   $coreNotif->getNbNotifications();
    }

    public function showEdit()
    {
        
    }

    public function showBody()
    {
        $content = '';
        foreach($this->notifications as $notification)
        {
            $display = new displayNotification($notification, $this->todisplay); //#todo OPTIMISER: ici on charge un affichage pour chaques user du tableau meme si celui ci a déjà été chargé
            $content = $content . $display->show();
        }

        return $content;
    }

    public function showEmpty()
    {
        return '<div class="bloc-container empty-recommended-container">
                     <div class="empty-title-container col-md-12">
                         <h1>'. $this->langFile[$this->pageName]->text_empty_notification .'</h1>
                     </div>   
                </div>';
    }

    public function showEmptyUm()
    {
        return '<div class="empty-title-container col-md-12">
                    <p>'. $this->langFileHeader->title_empty_notif .'</p>
               </div>';
    }

    public function showNotifBloc()
    {
        if(strlen($this->nbNotifs) >= 2)
        {
            $notifP = '<p class="lots-notifs">'. $this->nbNotifs .'</p>';
        }
        else{
            $notifP = '<p>'. $this->nbNotifs .'</p>';
        }

        if($this->nbNotifs == '0')
        {
            $notifP = '';
        }

        return '<div class="nbnotif-container">
                    <div class="nb-notifs">
                       '. $notifP .'
                    </div>
                </div>';
    }

    public function show()
    {
        if(Session::getInstance()->read('current-state')['state'] == 'owner')
        {
            if($this->notifications)
            {
                if($this->todisplay == 'NotificationsPage')
                {
                    return $this->showMyNotifications($this->showBody());
                }
                if($this->todisplay == 'UmNotifications')
                {
                    return $this->showMyNotificationsUm($this->showBody(), $this->showNotifBloc());
                }
            }
            else{
                if($this->todisplay == 'NotificationsPage')
                {
                    return $this->showMyNotificationsEmpty($this->showEmpty());
                }
                if($this->todisplay == 'UmNotifications')
                {
                    return $this->showMyNotificationsUmEmpty($this->showEmptyUm());
                }
            }
        }
        if(Session::getInstance()->read('current-state')['state'] == 'viewer')
        {
            if($this->notifications)
            {
                if($this->todisplay == 'UmNotifications')
                {
                    return $this->showMyNotificationsUm($this->showBody(), $this->showNotifBloc());
                }
            }
            else{
                if($this->todisplay == 'UmNotifications')
                {
                    return $this->showMyNotificationsUmEmpty($this->showEmptyUm());
                }
            }
        }
    }
}