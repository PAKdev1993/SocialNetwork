<?php

namespace app\Table\Posts;

use app\App;
use app\Table\Images\ImagesModel;
use core\Files\Images\Images;
use core\Functions;
use core\Session\Session;

class PostModel
{
    private $db;
    private $session;

    public function __construct()
    {
        $this->db = App::getDatabase();
        $this->session = Session::getInstance();
    }

    public function getMyPosts($begin, $nb)
    {
        $post = $this->db->query("SELECT * FROM we__Posts WHERE fk_iduser = ? ORDER BY date DESC LIMIT $begin, $nb ",[
            $this->session->read('auth')->pk_iduser,
        ])->fetchAll();
        return $post;
    }

    public function getPostsFromIdUser($begin, $nb, $userid)
    {
        $posts = $this->db->query("SELECT * FROM we__Posts WHERE fk_iduser = ? ORDER BY date DESC LIMIT $begin, $nb ",[
            $userid,
        ])->fetchAll();
        return $posts;
    }

    public function getPostFromUser($postid)
    {
        $post = $this->db->query("SELECT * FROM we__Posts WHERE pk_idpost = ? AND fk_iduser = ?",[
            $this->session->read('auth')->pk_iduser,
            $postid,
        ])->fetchAll();
        return $post;
    }

    public function getPostIdFromLastPostActivity($date)
    {
        $dateTime = \DateTime::createFromFormat('Y-m-d H:i:s', $date->last_post_activity)->format('Y-m-d H:i:s');
        $postid = $this->db->query("SELECT pk_idpost FROM we__Posts WHERE date = ? AND fk_iduser = ?",[
            $dateTime,
            $this->session->read('auth')->pk_iduser
        ])->fetch();
        return $postid->pk_idpost;
    }

    public function getAllPostsFromUser()
    {
        //return postsArray
    }

    public function getLastPostFromUser($iduser)
    {
        //return post;
    }

    public function getPostTextFromId($idpost)
    {
        $texte = $this->db->query("SELECT texte FROM we__Posts WHERE pk_idpost = ? AND fk_iduser = ?",[

        ]);
    }

    public function getLastsComunityPosts($limit = 10)
    {
        $ContactPosts = $this->db->query("SELECT * FROM we__Posts WHERE pk_idpost = ? AND fk_iduser = ?",[

        ]);
    }

    public function getImgStringFromProstid($postid)
    {
        $imgstring = $this->db->query("SELECT images FROM we__Posts WHERE pk_idpost = ? ",[$postid])->fetch()->images;
        return $imgstring;
    }


    /**
     * Delete un post ainsi que toutes ses images associés de la BD ET du serveur
     * @param $postid
     */
    public function deletePost($postid)
    {
        //avant de delete le post on recupère les slug des images qu'il contient pour les supprimer de la BD ET du serveur
        $imgString =        $this->getImgStringFromProstid($postid);
        $imgSlugArray =     explode('/',$imgString);
        $coreImg =          new Images(false, $this->session->read('auth')->pk_iduser);
        $imgModel =         new ImagesModel();
        foreach ($imgSlugArray as $imgSlug)
        {
            //DELETE FROM BD
            $imgModel->deleteImgFromSlugAndPostid($postid, $imgSlug);
            
            //DELETE FROM SERVER
            $coreImg->deleteImageFromSlugAndPostid($postid, $imgSlug);
        }

        //on supprime le post
        $this->db->query("DELETE FROM we__Posts WHERE pk_idpost = ?",[
            $postid
        ]);
    }

    public function updatePost($content = "", $imgs = [], $video = "")
    {

    }

    public function getPostDateFromId($postid)
    {
        $date = $this->db->query("SELECT date FROM we__Posts WHERE pk_idpost = ? ",[$postid])->fetch()->date;
        return $date;
    }

    public function getPostFromid($postid)
    {
        return $this->db->query("SELECT * FROM we__Posts WHERE pk_idpost = ? ",[$postid])->fetch();
    }
    
    public function getPostAuthor($postid)
    {
        return $this->db->query("SELECT fk_iduser FROM we__Posts WHERE pk_idpost = ? ",[$postid])->fetch()->fk_iduser;
    }

    public function getPostSuppressToken($postid)
    {
        return $this->db->query("SELECT suppressToken FROM we__Posts WHERE pk_idpost = ? ",[$postid])->fetch()->suppressToken;
    }

    public function getDateFromPostid($postid)
    {
        return $this->db->query("SELECT date FROM we__Posts WHERE pk_idpost = ? ",[$postid])->fetch()->date;
    }

    /**
     * @param $posiid
     * @return true si le post est dans les 25 deniers, false si non
     */
    public function isOldPost($postid)
    {
        //recuperation des ids nouveau posts
        $limit = 5; //same as max nb post displayed by page in profile/Timeline;
        $newestPosts =  $this->db->query("SELECT pk_idpost FROM we__Posts WHERE fk_iduser = ? ORDER BY date LIMIT 0,$limit",[
            $this->session->read('auth')->pk_iduser
        ])->fetchAll();

        $idsArray = Functions::getArrayFromObjectProperty($newestPosts, 'pk_idpost');
        $strindIds = implode(',',$idsArray);

        //test si la chaine de post est bien plein sinon erreur sql
        if(strlen($strindIds) == 0)
        {
            $nbPostFound = '0';
            return $nbPostFound;
        }

        //test si le post en cours fait partie des id des posts recents
        $nbPostFound = $this->db->query("SELECT COUNT(pk_idpost) as nbPostFound FROM we__Posts WHERE pk_idpost = ? AND pk_idpost IN($strindIds)",[
            $postid
        ])->fetch()->nbPostFound;

        //le post a été trouvé ds les posts recents, il n'est donc pas vieux
        if($nbPostFound == '1')
        {
            return false;
        }
        //le post n'a pas été trouvé ds les posts recents, il est vieux
        else{
            return true;
        }
    }

    public function isMyPostFromId($postid)
    {
        $nbPostFound = $this->db->query("SELECT COUNT(pk_idpost) as nbPostFound FROM we__Posts WHERE pk_idpost = ? AND fk_iduser = ?",[
            $postid,
            $this->session->read('auth')->pk_iduser
        ])->fetch()->nbPostFound;
        if($nbPostFound == '1')
        {
            return true;
        }
        //le post n'a pas été trouvé ds les posts recents, il est vieux
        else{
            return false;
        }
    }
}