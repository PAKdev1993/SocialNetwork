<?php

namespace app\Table\Comments;

use app\App;
use core\Session\Session;

class CommentModel
{
    private $db;
    private $comment;

    public function __construct()
    {
        $this->db = App::getDatabase();
        $this->session = Session::getInstance();
    }

    public function getCommentsFromPost($postid, $begin = 0, $limit = false){
        if(!$limit)
        {
            $limit = 10; //if u change this value, change it too in comlike.js on line 11
        }
        $end = $begin + $limit;
        return $this->db->query("SELECT * FROM `we__comments` WHERE fk_idpost = ? ORDER BY date DESC LIMIT $begin, $end", [$postid])->fetchAll();
    }

    public function getComment($comid){
        //
        return $this->comment;
    }

    //recuperation des posts qui n'ont pas été hidés, pour la timeline Home
    public function getPostToDisplayFromUserId($userid)
    {
        $post = $this->db->query(
            "SELECT * FROM `we__posts` WHERE fk_iduser = ? AND pk_idpost NOT IN ("
            ."SELECT fk_idpost FROM we__hidedposts WHERE fk_iduser = ?)"
            ."ORDER BY date DESC LIMIT 0,1",[
            $userid,
            $this->session->read('auth')->pk_iduser
        ])->fetch();

        return $post;
    }

    public function deletePost($post){
        //
    }

    public function getLastUserCommentFromPost($postid)
    {
        $userid = Session::getInstance()->read('auth')->pk_iduser;
        return $this->db->query("SELECT * FROM `we__comments` WHERE fk_idpost = ? AND fk_iduser = ? ORDER BY date DESC LIMIT 0,1", [$postid, $userid])->fetch();
    }

    public function getNbCommentToDisplayFromPost($postid)
    {
        return $this->db->query("SELECT COUNT(*) AS nb_coms FROM `we__comments` WHERE fk_idpost = ?", [$postid])->fetch()->nb_coms;
    }

}