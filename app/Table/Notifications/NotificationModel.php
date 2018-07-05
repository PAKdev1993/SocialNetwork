<?php
namespace app\Table\Notifications;

use app\App;
use core\Session\Session;

class NotificationModel
{
    private $db;
    private $session;

    public function __construct()
    {
        $this->db = App::getDatabase();
        $this->session = Session::getInstance();
    }

    public function getNbMyNotifications()
    {
        $nbNotifs = $this->db->query("SELECT COUNT(consulted) as nbNotifs FROM we__notifications WHERE fk_iduserto = ? AND consulted = ?",[
            $this->session->read('auth')->pk_iduser,
            0
        ])->fetch()->nbNotifs;
        return $nbNotifs;
    }
    
    public function saveNotification($from, $to, $codeNotif, $elemid)
    {
        $this->db->query("INSERT INTO we__notifications SET fk_iduserfrom = ?, fk_iduserto = ?, codeNotif = ?, elemid = ?", [
            $from,
            $to,
            $codeNotif,
            $elemid
        ]);
    }

    public function getMyNotificationsUnderMenu()
    {
        $notifs = $this->db->query("SELECT * FROM we__notifications WHERE fk_iduserto = ? AND consulted = ? ORDER BY date DESC",[
            $this->session->read('auth')->pk_iduser,
            0
        ])->fetchAll();
        return $notifs;
    }

    public function getAllMyNotifications()
    {
        $notifs = $this->db->query("SELECT * FROM we__notifications WHERE fk_iduserto = ? ORDER BY date DESC",[
            $this->session->read('auth')->pk_iduser
        ])->fetchAll();
        return $notifs;
    }
    
    public function consultMyNotifications()
    {
        $this->db->query("UPDATE we__notifications SET consulted = ? WHERE fk_iduserto = ?",[
            1,
            $this->session->read('auth')->pk_iduser
        ]);
    }

    public function consultMyNotificationFromId($idnotif)
    {
        $this->db->query("UPDATE we__notifications SET consulted = ? WHERE pk_notifEntry = ?",[
            1,
            $idnotif
        ]);
    }

    public function getAddreseeIdFromNotifId($idnotif)
    {
        $notif = $this->db->query("SELECT * FROM we__notifications WHERE pk_notifEntry = ?",[
            $idnotif
        ])->fetch();
        if($notif)
        {
            return $notif->fk_iduserto;
        }
        else{
            return false;
        }
    }

    public function notifExist($from, $to = false, $codeNotif, $elemid = NULL)
    {
        $dateNotif = $this->db->query("SELECT date FROM we__notifications WHERE fk_iduserfrom = ? AND fk_iduserto = ? AND codeNotif = ? AND elemid = ?",[
            $from,
            $to,
            $codeNotif,
            $elemid
        ])->fetch();

        if($dateNotif)
        {
            //si la notif ne porte pas sur un ajout d'user a notre liste d'amis alors on teste l'ancieneté de la presedente notif similaire
            $askContactNotif =  12;
            if($codeNotif != $askContactNotif)
            {
                //si la notification existe mais date de plus d'une heure alors on l'affiche
                $timeFirst  = strtotime($dateNotif->date);
                $timeSecond = strtotime(date("Y-m-d H:i:s"));
                $hour = 60*60;
                $differenceInSeconds = $timeSecond - $timeFirst - $hour;
                if($differenceInSeconds >= 0)
                {
                    return false;
                }
                else{
                    return true;
                }
            }
            //si la notif porte sur une demande d'ajout de contact, on ne considère pas que la notif existe deja
            else{
                return false;
            }
            
        }
        else{
            return false;
        }
    }
}