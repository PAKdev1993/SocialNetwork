<?php
namespace app\Table\Images;


use app\App;
use app\Table\Posts\PostModel;

use core\Functions;
use core\Files\Images\Images;
use core\Session\Session;

class ImagesModel
{
    private $db;
    private $session;

    public function __construct()
    {
        $this->db = App::getDatabase();
        $this->session = Session::getInstance();
    }

    public function saveImagePosted($idpost, $iduser, $imgname, $index)
    {
        $slug = strtoupper(substr(Functions::HASH('img', $idpost . $imgname . $index), 0, 20));

        $this->db->query('INSERT INTO `we__ImagesPosted` (`fk_iduser`, `fk_idpost`, `name`, `index`, `slug`) VALUES (?, ?, ?, ?, ?)', [
            $iduser,
            $idpost,
            $imgname,
            $index,
            $slug
        ]);
    }

    public function getImageFromSlugAndIduser($slug, $iduser)
    {
        $img = $this->db->query("SELECT * FROM we__ImagesPosted WHERE slug = ? AND fk_iduser = ?",[
            $slug,
            $iduser
        ])->fetch();
        return $img;
    }

    public function getImageNameFromSlugAndIduser($slug, $iduser)
    {
        $img = $this->db->query("SELECT name FROM we__ImagesPosted WHERE slug = ? AND fk_iduser = ?",[
            $slug,
            $iduser
        ])->fetch();
        if($img)
        {
            return $img->name;
        }
        else{
            return $img;
        }
    }

    public function getImagesFromPostId($idpost)
    {
        $imgs = $this->db->query("SELECT * FROM we__ImagesPosted WHERE fk_idpost = ?",[
            $idpost
        ])->fetchAll();
        return $imgs;
    }

    //#todo question, si le slug est unique (il l'est), la vitesse de la requette est-elle impactée par l'ajout du filter fk_idpost ?
    public function getImagesNameFromSlugAndPostId($slug, $idpost)
    {
        return $this->db->query("SELECT name FROM we__ImagesPosted WHERE slug = ? AND fk_idpost = ?",[
            $slug,
            $idpost
        ])->fetch()->name;
    }


    /**
     * Chercher une image par sulg ET post id permet d'eliminer toute probabilité qu'une image d'un autre user soit supprimé lorsqu'un user supprime l'un de ses posts
     * @param $postid
     * @param $newimgstring
     */
    public function deletePostImagesFromPostidAndNewImgString($postid, $newimgstring)
    {
        $postModel =    new PostModel();
        $post =         $postModel->getPostFromid($postid);
        $coreImg =      new Images(false, $post->fk_iduser);

        $oldImgArray =  explode('/',$post->images);
        $newImgArray =  explode('/', $newimgstring);
        $imgsToDelete = array_diff($oldImgArray, $newImgArray);

        foreach($imgsToDelete as $imgtoDelete)
        {
            //CALL TO DELETE IMG FROM SERVEUR
            $coreImg->deleteImageFromSlugAndPostid($postid, $imgtoDelete);

            //CALL TO DELETE IMG FROM BD
            $this->deleteImgFromSlugAndPostid($postid, $imgtoDelete);
        }
    }


    /**
     * Cette fonction permet de delete une image de la BD grace a sont identifiant slug (lequel est stocké ds le champ Images du serveur) et le post id,
     * ce qui permet de d'annuler la probabilité de delete la photo d'un autre user lorsqu'on delete une des notres
     * @param $postid
     * @param $slug
     */
    public function deleteImgFromSlugAndPostid($postid, $slug)
    {
        $this->db->query("DELETE FROM we__ImagesPosted WHERE fk_idpost = ? AND slug = ?",[
            $postid,
            $slug
        ]);
    }
    

    public function getDateFromSlugAndPostid($postid, $slug)
    {
        return $this->db->query("SELECT date FROM we__ImagesPosted WHERE slug = ? AND fk_idpost = ?",[
            $slug,
            $postid
        ])->fetch()->date;
    }

    public function getMyAlbumPreview()
    {
        return $this->db->query("SELECT * FROM we__ImagesPosted WHERE fk_iduser = ? ORDER BY date DESC LIMIT 0,6",[
            $this->session->read('auth')->pk_iduser
        ])->fetchAll();
    }

    public function getUserAlbumPreviewFromUserId($iduser)
    {
        return $this->db->query("SELECT * FROM we__ImagesPosted WHERE fk_iduser = ? ORDER BY date DESC LIMIT 0,6",[
            $iduser
        ])->fetchAll();
    }


    public function getAlbumPreviewFrimIduser($iduser)
    {
        return $this->db->query("SELECT * FROM we__ImagesPosted WHERE fk_iduser = ? ORDER BY date DESC LIMIT 0,6",[
           $iduser
        ])->fetchAll();
    }

    public function getImagesByMonthYear($month, $year, $iduser)
    {
        return $this->db->query("SELECT * FROM we__ImagesPosted WHERE fk_iduser = ? AND MONTH(date) = ? AND YEAR(date) = ? ORDER BY date DESC",[
            $iduser,
            $month,
            $year
        ])->fetchAll();
    }
}