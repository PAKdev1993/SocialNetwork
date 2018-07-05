<?php
namespace core\Timeline;

use app\App;
use core\Notifications\NotificationsManager;
use core\Session\Session;
use app\Table\Likes\LikeModel;

class Like
{

    private $db;
    private $session;

    private $userid;

    public function __construct()
    {
        $this->db = App::getDatabase();
        $this->session = Session::getInstance();
        $this->userid = $this->session->read('auth')->pk_iduser;
    }

    public function LikePost($postid){
        $model = new LikeModel();
        $like = $model->checkIfCurrentUserLikePost($postid);
        if(!$like)
        {
            //#todo call AddNotification here
            $this->db->query("INSERT INTO we__likesuser SET fk_iduser = ?, fk_idpost = ?, date = ?",[
                $this->userid,
                $postid,
                date("Y-m-d H:i:s")
            ]);

            //NOTIFICATION
            App::getNotificationManager()->createNotification($this->userid, false, 8, $postid);
        }
        else{
            $this->unLikePost($postid);
        }
    }

    public function unLikePost($postid)
    {
        $this->db->query("DELETE FROM we__likesuser WHERE fk_iduser = ? AND fk_idpost = ?",[
            $this->userid,
            $postid
        ]);
    }
}