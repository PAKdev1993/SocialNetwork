<?php

namespace app\Table\ManageAccount;

use app\App;
use core\Session\Session;

class ManageAccountModel
{
    private $db;
    private $session;

    public function __construct()
    {
        $this->db =         App::getDatabase();
        $this->session =    Session::getInstance();
    }
    
    public function getWishReceiveNotifWhen()
    {
        $notifWhen = $this->db->query('SELECT notif_when_user_wtb_part_of_network, notif_when_user_accept_contact_request, notif_when_user_like_post, notif_when_user_comment_post FROM we__accountmanaging WHERE fk_iduser = ?',[
            $this->session->read('auth')->pk_iduser
        ])->fetchAll();
        $category['notifWhen'] = $notifWhen;
        return $category;
    }
    
    public function getWishReceiveNotifWhenFromIdUser($iduser)
    {
        $notifWhen = $this->db->query('SELECT notif_when_user_wtb_part_of_network, notif_when_user_accept_contact_request, notif_when_user_like_post, notif_when_user_comment_post FROM we__accountmanaging WHERE fk_iduser = ?',[
            $iduser
        ])->fetchAll();
        $category['notifWhen'] = $notifWhen;
        return $category;
    }

    public function getWishReceiveEmailWhen()
    {
        $emailWhen = $this->db->query('SELECT email_when_user_wtb_part_of_network, email_when_user_accept_contact_request, email_when_user_like_post, email_when_user_comment_post, email_when_user_follow_you FROM we__accountmanaging WHERE fk_iduser = ?',[
            $this->session->read('auth')->pk_iduser
        ])->fetchAll();
        $category['emailWhen'] = $emailWhen;
        return $category;
    }

    public function getWishReceiveEmailWhenFromIdUser($iduser)
    {
        $emailWhen = $this->db->query('SELECT email_when_user_wtb_part_of_network, email_when_user_accept_contact_request, email_when_user_like_post, email_when_user_comment_post, email_when_user_follow_you FROM we__accountmanaging WHERE fk_iduser = ?',[
            $iduser
        ])->fetchAll();
        $category['emailWhen'] = $emailWhen;
        return $category;
    }

    public function getWishReceiveWeeklyEmailWhen()
    {
        $weeklyEmailWhen = $this->db->query('SELECT email_weekly_summary_of_visitors, email_weekly_list_of_recommended_contacts FROM we__accountmanaging WHERE fk_iduser = ?',[
            $this->session->read('auth')->pk_iduser
        ])->fetchAll();
        $category['weeklyEmailWhen'] = $weeklyEmailWhen;
        return $category;
    }

    public function saveAccountParameters($notifOnAsk, $notifOnAskAccepted, $notifOnLike, $notifOnComment, $emailOnAsk, $emailOnAskAccepted, $emailOnLike, $emailOnComment, $emailOnFollow, $weeklyEmailVisitors, $weeklyRecmdContacts)
    {
        $this->db->query('UPDATE we__accountmanaging SET 
                          notif_when_user_wtb_part_of_network = ?,
                          notif_when_user_accept_contact_request = ?,
                          notif_when_user_like_post = ?,
                          notif_when_user_comment_post = ?,
                          email_when_user_wtb_part_of_network = ?,
                          email_when_user_accept_contact_request = ?, 
                          email_when_user_like_post = ?,
                          email_when_user_comment_post = ?,
                          email_when_user_follow_you = ?,
                          email_weekly_summary_of_visitors = ?,
                          email_weekly_list_of_recommended_contacts = ? WHERE fk_iduser = ?',[
            $notifOnAsk,
            $notifOnAskAccepted,
            $notifOnLike,
            $notifOnComment,
            $emailOnAsk,
            $emailOnAskAccepted,
            $emailOnLike,
            $emailOnComment,
            $emailOnFollow,
            $weeklyEmailVisitors,
            $weeklyRecmdContacts,
            $this->session->read('auth')->pk_iduser
        ]);
    }
}