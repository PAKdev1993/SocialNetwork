<?php

namespace core\Comment;

use app\App;
use core\Functions;
use core\Notifications\NotificationsManager;
use core\Session\Session;

class Comment
{
    private $db;
    private $session;

    public function __construct()
    {
        $this->db = App::getDatabase();
        $this->session = Session::getInstance();
    }

    public function writeCommentPost($text, $postid)
    {         
        $this->db->query("INSERT INTO we__comments SET fk_iduser = ?, fk_idpost = ?, texte = ?, date = ?",[
            $this->session->read('auth')->pk_iduser,
            $postid,
            $text,
            date("Y-m-d H:i:s")
        ]);

        //NOTIFICATION
        App::getNotificationManager()->createNotification($this->session->read('auth')->pk_iduser, false, 9, $postid);
    }
}