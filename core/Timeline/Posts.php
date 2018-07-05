<?php

namespace core\Timeline;

use app\App;
use core\Session\Session;
use core\Actions\Action;
use core\Functions;

//#todo cette classe ne devrait elle pas etre ds les models? piste de reflexion
class Posts
{
    private $db;
    private $session;

    public function __construct()
    {
        $this->db = App::getDatabase();
        $this->session = Session::getInstance();
    }
    
    public function post($text, $previewContent = false, $imgString = false, $liveContent = false)
    {
        $text           = strip_tags($text, '<a><br>');
        //$previewContent = Functions::secureVarSQL($previewContent, '<iframe><a>');
        //$liveContent    = Functions::secureVarSQL($liveContent, '<iframe><a>');

        //l'action pour les post avec image est definie lor de la publication des images qui se fait après la publication du post: ImageModel->saveImgPosted
        $postActionName = '';
        //cas on a modifié un post en retirant toute ses images
        if($imgString == '')
        {
            $action = new Action();
            $postActionName = $action->definePostAction($imgString, false);
        }
        //cas le post est un live
        if($liveContent)
        {
            $action = new Action();
            $postActionName = $action->definePostAction(false, 'live');
        }
        //create suppress token
        $suppressToken = Functions::str_random(60);

        //post
        $this->db->query("INSERT INTO we__posts SET fk_iduser = ?, texte = ?, previewContent = ?, liveContent = ?, action = ?, date = ?, suppressToken = ?",[
            $this->session->read('auth')->pk_iduser,
            $text,
            $previewContent,
            $liveContent,
            $postActionName,
            date("Y-m-d H:i:s"),
            $suppressToken
        ]);
        //#todo call AddNotification here
    }

    public function editPost($postid, $newText, $newPreview, $newImgString = 'noNeedImgUpdate')
    {
        //$newText        = Functions::secureVarSQL($newText, '<a>');
        //$newPreview     = Functions::secureVarSQL($newPreview, '<iframe><a>');

        //equivaut a : les images du nouveau post n'ont pas changé
        if($newImgString == 'noNeedImgUpdate')
        {
            $this->db->query("UPDATE we__posts SET texte = ?, previewContent = ? WHERE pk_idpost = ?",[
                $newText,
                $newPreview,
                $postid
            ]);
        }

        else{
            $action = new Action();
            $postActionName = $action->definePostAction($newImgString);

            $this->db->query("UPDATE we__posts SET texte = ?, previewContent = ?, images = ?, action = ? WHERE pk_idpost = ?",[
                $newText,
                $newPreview,
                $newImgString,
                $postActionName,
                $postid
            ]);
        }
    }

    public function deletePost($postid)
    {
        $this->db->query("DELETE FROM we__user WHERE pk_idpost = ?",[
            $postid
        ]);
    }

    public function addImagesToPost($postid, $imgString)
    {
        $action = new Action();
        $postActionName = $action->definePostAction($imgString);

        //$imgString = Functions::secureVarSQL($imgString, '<img><a>');

        $this->db->query("UPDATE we__posts SET action = ?, images = ? WHERE pk_idpost = ?",[
            $postActionName,
            $imgString,
            $postid
        ]);
    }

    public function hidePost($postid)
    {
        $this->db->query("INSERT INTO we__hidedposts SET fk_idpost = ?, fk_iduser = ?, date = ?",[
            $postid,
            $this->session->read('auth')->pk_iduser,
            date("Y-m-d H:i:s")
        ]);
    }

    public function hideUserPosts($iduserhided)
    {
        $this->db->query("INSERT INTO we__usershided SET fk_iduser = ?, id_userHided = ?, date = ?",[
            $this->session->read('auth')->pk_iduser,
            $iduserhided,
            date("Y-m-d H:i:s")
        ]);
    }

    public function postNotifMyNetwork($typeaction, $action, $elemType, $elemid)
    {
        //#todo mettre a jour la date du p
        $suppressToken = Functions::str_random(60);

        //erreur de raisonnement l'action est traduite a l'affichage, seul le type est inscrit ds la bd$actionName = App::getLangModel()->getActionTraduce($postActionName);

        $this->db->query("INSERT INTO we__posts SET fk_iduser = ?, date = ?, typeNotifyMyNetwork = ?, type_action = ?, action = ?, elem_type = ?, elem_id = ?, suppressToken = ?",[
            $this->session->read('auth')->pk_iduser,
            date("Y-m-d H:i:s"),
            1,
            $typeaction,
            $action,
            $elemType,
            $elemid,
            $suppressToken
        ]);
    }

    public function checkIntervalLastNotifyPost($typeaction)
    {
        //get le dernier post de type notifyMyNetwork ayant la meme actiontype
        $date = $this->db->query("SELECT date FROM we__posts WHERE fk_iduser = ? AND type_action = ? AND typeNotifyMyNetwork = ? ORDER BY date DESC LIMIT 0,1",[
            $this->session->read('auth')->pk_iduser,
            $typeaction,
            1
        ])->fetch();

        if($date)
        {
            //retur true si l'interval entre l'heure actuelle et celle du dernier post est inferieure a 1heure
            $datetime1 = time();
            $datetime2 = strtotime($date->date);
            $hour = ($datetime1 - $datetime2)/3600;
            $valid = $hour > 1;
        }
        else {
            $valid = true;
        }
        return $valid;
    }
    
    public function updateDateLastNotifyMyNetworkPost($typeaction)
    {
        //get last post id to modify, car impossible de select l'id d'un post lor d'une requette d'update sur cette meme table
        $lastPostId = $this->db->query("SELECT pk_idpost FROM we__posts WHERE fk_iduser = ? AND type_action = ? AND typeNotifyMyNetwork = ? ORDER BY date DESC LIMIT 0,1",[
            $this->session->read('auth')->pk_iduser,
            $typeaction,
            1
        ])->fetch()->pk_idpost;

        //update la date du dernier post de notifyMynetwork
        $this->db->query("UPDATE we__posts SET date = ? WHERE pk_idpost = ?",[
            date("Y-m-d H:i:s"),
            $lastPostId
        ]);
    }
}