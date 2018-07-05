<?php

namespace app\Table\Likes;

use app\App;
use core\Session\Session;

class LikeModel
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

    public function getNbLikesFromPost($postid){
        return $this->db->query("SELECT COUNT(*) AS nb_likes FROM we__likesuser WHERE fk_idpost = ?",[
                    $postid
                ])->fetch()->nb_likes;

    }

    public function getLikeFromUser($post, $user){
        //
        return ;
    }
    
    public function checkIfCurrentUserLikePost($postid)
    {
        $like = $this->db->query("SELECT * FROM we__likesuser WHERE fk_idpost = ? AND fk_iduser = ?",[
            $postid,
            $this->userid
            ])->fetch();

        if($like)
        {
            return true;
        }
        else{
            return false;
        }
    }
}