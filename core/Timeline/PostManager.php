<?php
/**
 * Created by PhpStorm.
 * User: PAK
 * Date: 27/08/2016
 * Time: 19:55
 */

namespace core\Timeline;


use app\Table\Comments\CommentModel;
use app\Table\Posts\MyTimeline\displayMyPost;
use app\Table\Posts\MyTimeline\displayMyPostNotifyMyNetwork;
use app\Table\Posts\PostModel;
use app\Table\UserModel\UserModel;

class PostManager extends Timeline
{
    private $postid;
    
    public function __construct($postid)
    {
        parent::__construct();
        $this->postid = $postid;
    }

    public function getMyPost()
    {
        $model =        new PostModel();
        $CommentModel = new CommentModel();
        $Timeline =     new Timeline();
        
        $post = $model->getPostFromid($this->postid);
        $comments = $CommentModel->getCommentsFromPost($this->postid);
        $commentDisplayMode = $Timeline->getCommentDisplayMode(sizeof($comments));

        //Le post est un post de notify my network
        if($post->typeNotifyMyNetwork){
            $model = new UserModel();
            $user = $model->getUserFromId($post->fk_iduser);
            $display = new displayMyPostNotifyMyNetwork($post, $user, $comments, $commentDisplayMode);
        }
        //Le post est un post classique
        else{
            $display = new displayMyPost($post, $comments, $commentDisplayMode);
        }

        //write post id in session
        $this->writePostsIdInSession([$post->pk_idpost]);

        //display
        return $display->show();
    }
}